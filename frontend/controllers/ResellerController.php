<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\components\acf\ResellerAccessRule;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use frontend\components\cart\Cart;
use frontend\components\cart\CartItem;
use frontend\components\cart\CartItemReseller;
use frontend\components\cart\CartPromotion;
use frontend\models\Order;
use frontend\models\Game;
use frontend\models\UserWallet;
use frontend\models\PromotionApply;
use yii\base\Model;

/**
 * ResellerController
 */
class ResellerController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                
            ],
        ];
    }

    public function actionDownload()
    {
        $settings = Yii::$app->settings;
        $template = $settings->get('ImportSettingForm', 'import_reseller_template', null);
        if (file_exists($template)) {
           Yii::$app->response->sendFile($template, sprintf("template%s.xlsx", date('Ymd')));
        } 
    }

    public function actionImport($id)
    {
        $request = Yii::$app->request;
        $userId = Yii::$app->user->id;
        $validRecords = $invalidRecords = [];
        if ($request->isPost) {
            $files = Yii::$app->file->upload('template', "template/$userId");
            $inputFile = reset($files);//Yii::getAlias('@frontend') . '/template_1.xlsx';
            $inputFile = Yii::$app->file->getPath($inputFile);
            $game = CartItemReseller::findOne($id);
            try{
                $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFile);
            } catch (Exception $e) {
                die('Error');
            }
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $startRow = 11;
            $records = [];        
            $rowData = $sheet->rangeToArray(sprintf("%s%s:%s%s", 'A', $startRow, $highestColumn, $highestRow), null, true, false);
            
            foreach ($rowData as $no => $data) {
                $item = clone $game;
                $item->row_index = $startRow + $no;
                $item->no = $data[0];
                if (!$data[0]) continue;
                $item->quantity = $data[1];
                $item->username = $data[4];
                $item->password = $data[5];
                $item->character_name = $data[3];
                $item->recover_code = $data[7];
                $item->server = $data[6];
                $item->note = $data[9];
                $item->platform = $data[8];
                $item->login_method = $data[2];
                $item->setScenario(CartItemReseller::SCENARIO_IMPORT_CART);
                $records[] = $item;
            }
            $validRecords = array_filter($records, function($item) {
                return $item->validate();
            });
            $invalidRecords = array_filter($records, function($item) {
                return !$item->validate();
            });
        }
        return $this->render('import', [
            'id' => $id,
            'valid_records' => $validRecords,
            'invalid_records' => $invalidRecords
        ]);
    }

   
    public function actionPurchase($id)
    {
        $user = Yii::$app->user->getIdentity();
        $request = Yii::$app->request;
        $game = CartItemReseller::findOne($id);
        if (!$game) throw new NotFoundHttpException("Not found", 1);
        $imports = $request->post('import', []);
        $errors = [];
        foreach ($imports as $importData) {
            $cartItem = clone $game;
            $cartItem->quantity = $importData['quantity'];
            $cartItem->username = $importData['username'];
            $cartItem->password = $importData['password'];
            $cartItem->character_name = $importData['character_name'];
            $cartItem->recover_code = $importData['recover_code'];
            $cartItem->server = $importData['server'];
            $cartItem->note = $importData['note'];
            $cartItem->platform = $importData['platform'];
            $cartItem->login_method = $importData['login_method'];
            $cartItem->setScenario(CartItemReseller::SCENARIO_IMPORT_CART);
            $totalPrice = $cartItem->getTotalPrice();
            $balance = $user->getWalletAmount();
            if ($totalPrice > $balance) {
                $errors[] = 'Not enough balance in your wallet';
                break;
            } elseif ($cartItem->validate()) {
                $totalUnit = $cartItem->getTotalUnit();
                
                // Order detail
                $order = new Order();
                $order->sub_total_price = $totalPrice;
                $order->total_discount = 0;
                $order->total_price = $totalPrice;
                $order->customer_id = $user->id;
                $order->customer_name = $user->name;
                $order->customer_email = $user->email;
                $order->customer_phone = $user->phone;
                $order->status = Order::STATUS_PENDING;
                $order->payment_at = date('Y-m-d H:i:s');
                $order->generateAuthKey();
    
                // Item detail
                $order->game_id = $cartItem->id;
                $order->game_title = $cartItem->getLabel();
                $order->quantity = $cartItem->quantity;
                $order->unit_name = $cartItem->unit_name;
                $order->sub_total_unit = $totalUnit;
                $order->promotion_unit = 0;
                $order->total_unit = $totalUnit;
                $order->username = $cartItem->username;
                $order->password = $cartItem->password;
                $order->platform = $cartItem->platform;
                $order->login_method = $cartItem->login_method;
                $order->character_name = $cartItem->character_name;
                $order->recover_code = $cartItem->recover_code;
                $order->server = $cartItem->server;
                $order->note = $cartItem->note;
                $order->save();
    
                $wallet = new UserWallet();
                $wallet->coin = (-1) * $totalPrice;
                $wallet->balance = $user->getWalletAmount() + $wallet->coin;
                $wallet->type = UserWallet::TYPE_OUTPUT;
                $wallet->description = "Pay for order #$order->id";
                $wallet->ref_name = Order::classname();
                $wallet->ref_key = $order->id;
                $wallet->created_by = $user->id;
                $wallet->user_id = $user->id;
                $wallet->status = UserWallet::STATUS_COMPLETED;
                $wallet->payment_at = date('Y-m-d H:i:s');
                $wallet->save();    
            } else {
                $error = $cartItem->getErrorSummary(true);
                $errors[] = $error[0];
            }
        }
        return $this->render('purchase', [
            'errors' => $errors
        ]);
    }

    public function actionBulk($id)
    {
        $singleItem = CartItem::findOne($id);
        $singleItem->setScenario(CartItemReseller::SCENARIO_IMPORT_RAW);
        $models = [$singleItem];
        $user = Yii::$app->user->identity;
        $balance = $user->getWalletAmount();
        if (Model::loadMultiple($models, Yii::$app->request->post())) {
            // Validate
            $modelQuantities = ArrayHelper::getColumn($models, 'quantity');
            $totalQuantities = array_sum($modelQuantities);
            $singleItem->quantity = $totalQuantities;
            $totalPrice = $singleItem->getTotalPrice();
            if ($balance >= $totalPrice) {
                foreach ($models as $cartItem) {
                    $cartItem->setScenario(CartItemReseller::SCENARIO_IMPORT_RAW);
                    $totalPrice = $cartItem->getTotalPrice();
                    $balance = $user->getWalletAmount();
                    if ($totalPrice > $balance) {
                        $errors[] = 'Not enough balance in your wallet';
                        break;
                    } elseif ($cartItem->validate()) {
                        $totalUnit = $cartItem->getTotalUnit();
                        
                        // Order detail
                        $order = new Order();
                        $order->sub_total_price = $totalPrice;
                        $order->price = $totalPrice;
                        $order->cogs_price = $totalPrice;
                        $order->total_discount = 0;
                        $order->total_price = $totalPrice;
                        $order->customer_id = $user->id;
                        $order->customer_name = $user->name;
                        $order->customer_email = $user->email;
                        $order->customer_phone = $user->phone;
                        $order->status = Order::STATUS_PENDING;
                        $order->payment_at = date('Y-m-d H:i:s');
                        $order->raw = $cartItem->raw;
                        $order->generateAuthKey();
            
                        // Item detail
                        $order->game_id = $id;
                        $order->game_title = $cartItem->getLabel();
                        $order->quantity = $cartItem->quantity;
                        $order->unit_name = $cartItem->unit_name;
                        $order->sub_total_unit = $totalUnit;
                        $order->promotion_unit = 0;
                        $order->total_unit = $totalUnit;
                        $order->save();
            
                        $wallet = new UserWallet();
                        $wallet->coin = (-1) * $totalPrice;
                        $wallet->balance = $user->getWalletAmount() + $wallet->coin;
                        $wallet->type = UserWallet::TYPE_OUTPUT;
                        $wallet->description = "Pay for order #$order->id";
                        $wallet->ref_name = Order::classname();
                        $wallet->ref_key = $order->id;
                        $wallet->created_by = $user->id;
                        $wallet->user_id = $user->id;
                        $wallet->status = UserWallet::STATUS_COMPLETED;
                        $wallet->payment_at = date('Y-m-d H:i:s');
                        $wallet->save();    
                    } else {
                        $error = $cartItem->getErrorSummary(true);
                        $errors[] = $error[0];
                    }
                }
            }
        }
        return $this->render('bulk', [
            'models' => $models,
            'balance' => $balance
        ]);
    }
}