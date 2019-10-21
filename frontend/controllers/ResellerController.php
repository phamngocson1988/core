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
use frontend\forms\FetchHistoryOrderForm;
use yii\data\Pagination;

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
        $request = Yii::$app->request;
        $user = Yii::$app->user->identity;
        $singleItem = CartItem::findOne($id);
        $singleItem->setScenario(CartItemReseller::SCENARIO_IMPORT_RAW);
        $models = [];
        $balance = $user->getWalletAmount();
        if ($request->isPost) {
            $data = Yii::$app->request->post('CartItem', []);
            foreach ($data as $attr) {
                $item = clone $singleItem;
                $item->attributes = $attr;
                $models[] = $item;
            }
            if (Model::validateMultiple($models)) {
                $modelQuantities = ArrayHelper::getColumn($models, 'quantity');
                $totalQuantities = array_sum($modelQuantities);
                $singleItem->quantity = $totalQuantities;
                $totalPrice = $singleItem->getTotalPrice();
                if ($balance >= $totalPrice) {
                    $bulk = strtotime('now');
                    foreach ($models as $cartItem) {
                        $totalPrice = $cartItem->getTotalPrice();
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
                        $order->bulk = $bulk;
            
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
                    }
                    return $this->redirect(['reseller/success', 'bulk' => $bulk]);
                } else {
                    Yii::$app->session->setFlash('error', 'You have not enough Kcoin.');
                }
            }
        }
        
        return $this->render('bulk', [
            'models' => $models,
            'balance' => $balance,
            'default_price' => $singleItem->getPrice(),
            'title' => $singleItem->title,
        ]);
    }

    public function actionSuccess($bulk)
    {
        $orders = Order::find()->where(['bulk' => $bulk])->all();
        $user = Yii::$app->user->getIdentity();
        return $this->render('success', [
            'orders' => $orders,
            'user' => $user,
        ]);
    }

    public function actionOrder() 
    {
        $request = Yii::$app->request;
        $status = $request->get('status');

        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['user_menu_active'] = "reseller.$status";
        $filter = [
            'customer_id' => Yii::$app->user->id,
            'status' => $status,
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];
        $form = new FetchHistoryOrderForm($filter);

        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('orders', [
            'models' => $models,
            'pages' => $pages,
            'filterForm' => $form,
            'status' => $status,
            'showFilter' => $request->get('filter')
        ]);

        return $this->render('orders');
    }
}