<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

use frontend\components\cart\Cart;
use frontend\components\cart\CartItemImportBehavior;
use frontend\components\cart\CartItem;
use frontend\components\cart\CartPromotion;
use frontend\models\Order;
use frontend\models\UserWallet;
use frontend\models\PromotionApply;

/**
 * ResellerController
 */
class ResellerController extends Controller
{
    public function actionDownload()
    {
        $settings = Yii::$app->settings;
        $template = $settings->get('ImportSettingForm', 'import_reseller_template', null);
        if (file_exists($template)) {
           Yii::$app->response->sendFile($template);
        } 
    }
    public function actionDownload1($id) 
    {
        $game = Game::findOne($id);
        if (!$game) throw new NotFoundHttpException("Not found", 1);
        $fileName = sprintf("template_%s.xlsx", $game->id);
        $titles = [
            'A' => 'No',
            'B' => 'Amount',
            'C' => 'Login method',
            'D' => 'Character name',
            'E' => 'ID / Username',
            'F' => 'Password',
            'G' => 'Server',
            'H' => 'Recovery code',
            'I' => 'Remark'
        ];
        $totalRow = 1;
        $startRow = 5;
        $endRow = $startRow + $totalRow;
        $footerRow = $endRow + 1;
        $columns = array_keys($titles);
        $startColumn = reset($columns);
        $endColumn = end($columns);;
        $rangeTitle = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $startRow);
        $rangeData = sprintf('%s%s:%s%s', $startColumn, $startRow + 1, $endColumn, $endRow);
        $rangeTable = sprintf('%s%s:%s%s', $startColumn, $startRow, $endColumn, $endRow);

        $heading = 'THE MORE CAREFULLY YOU DO THIS, THE FASTER PROCESS WOULD BE!';
        $header = [
            'A2:I2' => 'Thanks for your orders, that would be our pleasure to cooperate with you!!!',
            'A3:I3' => 'List of orders',
        ];
        
        $data[] = ['1', '', '', '', '', '', '', '', ''];

        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'writerClass' => '\PHPExcel_Writer_Excel5', //\PHPExcel_Writer_Excel2007
            'sheets' => [
                'Orders' => [
                    'class' => 'common\components\export\excel\ExcelSheet',//'codemix\excelexport\ExcelSheet',
                    'heading' => $heading,
                    'header' => $header,
                    'data' => $data,
                    'startRow' => $startRow,
                    'titles' => $titles,
                    'styles' => [
                        $rangeTitle => [
                            'font' => [
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            ],
                        ],
                        $rangeTable => [
                            'borders' => array(
                                'allborders' => array(
                                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        ],
                    ],
                    
                    'on beforeRender' => function ($event) {
                        $sender = $event->sender;
                        $sheet = $sender->getSheet();
                        $sender->renderHeader();
                        $sender->renderFooter();
                        $titles = $sender->getTitles();
                        $columns = array_keys($titles);
                        foreach ($columns as $column) {
                            $sheet->getColumnDimension($column)->setAutoSize(true);
                        }
                    },
                    'on afterRender' => function($event) {
                        $sheet = $event->sender->getSheet();
                        $sheet->setSelectedCell("A1");
                    }
                ],
            ],
        ]);
        $file->send($fileName);
    }

    public function actionImport()
    {
        $inputFile = Yii::getAlias('@frontend') . '/template_1.xlsx';
        $id = 1;
        $game = CartItem::findOne($id);
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
        $startRow = 6;
        $records = [];        
        $rowData = $sheet->rangeToArray(sprintf("%s%s:%s%s", 'A', $startRow, $highestColumn, $highestRow), null, true, false);
        
        foreach ($rowData as $no => $data) {
            $item = clone $game;
            $item->attachBehavior('import', new CartItemImportBehavior);
            $item->row_index = $startRow + $no;
            $item->no = $data[0];
            $item->quantity = $data[1];
            $item->username = $data[4];
            $item->password = $data[5];
            $item->character_name = $data[3];
            $item->recover_code = $data[7];
            $item->server = $data[6];
            $item->note = $data[8];
            $item->platform = 'ios';
            $item->login_method = $data[2];
            $item->setScenario(CartImportItem::SCENARIO_IMPORT_CART);
            $records[] = $item;
        }
        $validRecords = array_filter($records, function($item) {
            return $item->validate();
        });
        $invalidRecords = array_filter($records, function($item) {
            return !$item->validate();
        });

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
        $game = CartImportItem::findOne($id);
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
            $cartItem->setScenario(CartImportItem::SCENARIO_IMPORT_CART);
            if ($cartItem->validate()) {
                $totalPrice = $cartItem->getTotalPrice();
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


}