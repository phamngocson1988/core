<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

use frontend\components\cart\Cart;
use frontend\components\cart\CartItem;
use frontend\components\cart\CartPromotion;
use frontend\models\Order;
use frontend\models\UserWallet;
use frontend\models\PromotionApply;

/**
 * CartController
 */
class CartController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'add', 'update', 'checkout', 'purchase'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['add', 'update'],
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                    [
                        'actions' => ['checkout', 'purchase'],
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add' => ['post'],
                    'update' => ['post'],
                    'purchase' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $cart = Yii::$app->cart;
        $promotion_code = $request->post('promotion_code');
        $item = $cart->getItem();
        if (!$item) return $this->redirect(['site/index']);
        $item->setScenario($request->post('scenario'));
        if ($request->isPost) {
            if ($item->load($request->post()) && $item->validate()) {
                $cart->add($item);
                if ($item->scenario == CartItem::SCENARIO_EDIT_CART) {
                    $discount = CartPromotion::findOne([
                        'code' => $promotion_code,
                        'promotion_scenario' => CartPromotion::SCENARIO_BUY_GEMS,
                    ]);

                    if ($discount) {
                        $discount->setScenario(CartPromotion::SCENARIO_ADD_PROMOTION);
                        $discount->user_id = Yii::$app->user->id;
                        $discount->game_id = $item->id;
                        if (!$discount->validate() || !$discount->code) $cart->removePromotionItem();                            
                        else {
                            $cart->setPromotionItem($discount);
                            $cart->applyPromotion();
                        }
                    } else {
                        $cart->removePromotionItem();
                    }
                    
                } elseif ($item->scenario == CartItem::SCENARIO_INFO_CART) {
                    return $this->redirect(Url::to(['cart/checkout']));
                } 
            }
        }

        return $this->render('index', [
            'cart' => $cart,
            'promotion_code' => $promotion_code,
            'item' => $item
        ]);
    }

    public function actionAdd($id)
    {
    	$request = Yii::$app->request;
    	if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
    	if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => null, 'errors' => []]);

        $cart = Yii::$app->cart;
        $cart->clear();
        $item = CartItem::findOne($id);
        $item->setScenario(CartItem::SCENARIO_ADD_CART);
        if ($item->load($request->post()) && $item->validate()) {
            $cart->add($item);
            return json_encode([
                'status' => true, 
                'user_id' => Yii::$app->user->id, 
                'cart' => $cart->getItems(),
                'cart_url' => Url::to(['cart/index'])
            ]);
        } else {
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $item->getErrors()]);
        }
    }

    public function actionUpdate()
    {
    	$request = Yii::$app->request;
    	if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
    	if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => null, 'errors' => []]);
        $cart = Yii::$app->cart;
        $item = $cart->getItem();
        $item->setScenario(CartItem::SCENARIO_INFO);
        if ($item->load($request->post()) && $item->validate()) {
            $cart->add($item);
            return json_encode(['status' => true, 'user_id' => Yii::$app->user->id, 'checkout_url' => Url::to(['cart/checkout'])]);
        } else {
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $item->getErrors()]);
        }
    }

    public function actionCheckout()
    {
        $cart = Yii::$app->cart;
        $user = Yii::$app->user->getIdentity();
        $canPlaceOrder = $user->getWalletAmount() > $cart->getTotalPrice();
        return $this->render('checkout', ['can_place_order' => $canPlaceOrder]);
    }

    public function actionPurchase()
    {
        // Create order
        $user = Yii::$app->user->getIdentity();
        $cart = Yii::$app->cart;
        if ($cart->hasPromotion()) {
            $cart->applyPromotion();
        }
        $totalPrice = $cart->getTotalPrice();
        $subTotalPrice = $cart->getSubTotalPrice();
        $promotionCoin = $cart->getPromotionCoin();
        $promotionUnit = $cart->getPromotionUnit();

        $cartItem = $cart->getItem();
        // Order detail
        $order = new Order();
        $order->sub_total_price = $subTotalPrice;
        $order->total_discount = $promotionCoin;
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
        $order->sub_total_unit = $cartItem->getTotalUnit();
        $order->promotion_unit = $promotionUnit;
        $order->total_unit = $cartItem->getTotalUnit() + $promotionUnit;
        $order->username = $cartItem->username;
        $order->password = $cartItem->password;
        $order->platform = $cartItem->platform;
        $order->login_method = $cartItem->login_method;
        $order->character_name = $cartItem->character_name;
        $order->recover_code = $cartItem->recover_code;
        $order->server = $cartItem->server;
        $order->note = $cartItem->note;

        if (!$order->save()) throw new BadRequestHttpException("Error Processing Request", 1);

        if ($cart->hasPromotion()) {
            $promotionItem = $cart->getPromotionItem();
            $apply = new PromotionApply();
            $apply->promotion_id = $promotionItem->id;
            $apply->user_id = $user->id;
            $apply->save();
        }

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

        // Send mail to customer
        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
        if ($adminEmail) {
            $email = Yii::$app->mailer->compose()
            ->setTo($user->email)
            ->setFrom([$adminEmail => Yii::$app->name . ' Administrator'])
            ->setSubject("Order #$order->id Confirmation")
            ->setTextBody("Thanks for your order")
            ->send();
        }
        // $this->layout = 'notice';
        return $this->render('/site/notice', [           
            'title' => 'Place order successfully',
            'content' => 'Congratulation.'
        ]);
    }

    public function actionReseller()
    {
        
    }

    public function actionDownload() 
    {
        $fileName ='template.xlsx';
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
        
        // $data[] = ['1', '', '', '', '', '', '', '', ''];
        $data = [];

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
    $inputFile = Yii::getAlias('@frontend') . '/template.xlsx';
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

    for($row=1; $row <= $highestRow; $row++)
    {
        $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);

        if($row==1)
        {
            continue;
        }
        print_r($rowData);
    }
    die('okay');
}
}