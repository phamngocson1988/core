<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use frontend\components\cart\Cart;
use frontend\components\cart\CartItem;
use frontend\components\cart\CartPromotion;
use frontend\models\Order;
use frontend\models\User;
use frontend\events\ShoppingEventHandler;

use frontend\components\payment\cart\PaymentItem;
use frontend\components\payment\cart\PaymentCart;
use frontend\components\payment\cart\PaymentPromotion;
use frontend\components\payment\PaymentGateway;
use frontend\components\payment\PaymentGatewayFactory;
use common\components\helpers\FormatConverter;
use frontend\behaviors\OrderLogBehavior;

/**
 * CartController
 */
class CartController extends Controller
{
    // public $enableCsrfValidation = false;
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

    public function beforeAction($action)
    {            
        if ($action->id == 'verify') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
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
                    return $this->redirect(Url::to(['cart/confirm']));
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

    public function actionConfirm()
    {
        $request = Yii::$app->request;
        $user = User::findOne(Yii::$app->user->id);
        $cart = Yii::$app->cart;
        $item = $cart->getItem();
        if (!$item) return $this->redirect(['site/index']);
        $item->setScenario(CartItem::SCENARIO_RECEPTION_CART);
        if ($item->load($request->post()) && $item->validate()) {
            $cart->add($item);
            return $this->redirect(['cart/checkout']);
        }
        if (!$item->reception_email) $item->reception_email = $user->email;
        return $this->render('confirm', ['cart' => $cart]);
    }

    public function actionCheckout()
    {
        $settings = Yii::$app->settings;
        $paypalMode = $settings->get('PaypalSettingForm', 'mode', 'sandbox');
        if ($paypalMode == 'live') {
            $clientId = $settings->get('PaypalSettingForm', 'client_id');
        } else {
            $clientId = $settings->get('PaypalSettingForm', 'sandbox_client_id');
        }

        $this->view->registerJsFile("https://www.paypal.com/sdk/js?client-id=$clientId&disable-card=visa,mastercard,amex,discover,jcb,elo,hiper", ['position' => \yii\web\View::POS_HEAD]);

        $cart = Yii::$app->cart;
        $user = Yii::$app->user->getIdentity();
        $balance = $user->getWalletAmount();
        $canPlaceOrder = $balance >= $cart->getTotalPrice();
        return $this->render('checkout', [
            'can_place_order' => $canPlaceOrder,
            'balance' => $balance
        ]);
    }

    public function actionPurchase()
    {
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        $reseller = $user->reseller; 
        $identifier = $request->post('identifier');
        if (!$identifier) {
            Yii::$app->session->setFlash('error', 'You must choose a payment gateway');
            return $this->redirect(['cart/checkout']);
        }
        $paymentCart = new PaymentCart([
            'title' => 'Pay for buying Game',
        ]);
        $cart = Yii::$app->cart;
        if (!$cart->getItems()) {
            Yii::$app->session->setFlash('error', 'Your cart is empty');
            return $this->redirect(['game/index']);
        }
        $cartItem = $cart->getItem();
        $paymentCartItem = new PaymentItem([
            'id' => $cartItem->getUniqueId(),
            'title' => $cartItem->getLabel(),
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->getPrice()
        ]);
        $paymentCart->addItem($paymentCartItem);
        
        if ($cart->hasPromotion()) {
            $cart->applyPromotion();
            if ($cart->getPromotionMoney()) {
                $promotionItem = $cart->getPromotionItem();
                $paymentPromotion = new PaymentPromotion([
                    'id' => $promotionItem->code,
                    'title' => 'promotion promotion code ' . $promotionItem->code,
                    'price' => $cart->getPromotionMoney()
                ]);
                $paymentCart->setPromotion($paymentPromotion);
            }
        }
        $gateway = PaymentGatewayFactory::getClient($identifier);
        $gateway->confirm_url = 'cart/verify';
        $gateway->success_url = 'cart/success';
        $gateway->cancel_url = 'cart/cancel';
        $gateway->error_url = 'cart/error';
        $gateway->setCart($paymentCart);
        if (!$gateway->validatePayment()) {
            Yii::$app->session->setFlash('error', $gateway->getErrorSummary(true));
            return $this->redirect(['cart/checkout']);
        }
        
        try {
            // Create order
            $totalPrice = $cart->getTotalPrice();
            $subTotalPrice = $cart->getSubTotalPrice();
            $promotionCoin = $cart->getPromotionCoin();
            $promotionUnit = $cart->getPromotionUnit();

            // Order detail
            $order = new Order();
            $order->attachBehavior('log', OrderLogBehavior::className());
            $order->payment_method = $identifier;
            $order->payment_type = $gateway->type;
            // $order->payment_id = $gateway->getReferenceId();
            $order->price = $cartItem->getPrice();
            $order->cogs_price = $cartItem->getCogs();
            $order->sub_total_price = $subTotalPrice;
            $order->total_discount = $promotionCoin;
            $order->total_price = $totalPrice;
            $order->total_price_by_currency = FormatConverter::convertCurrencyToCny($totalPrice);
            $order->currency = $gateway->currency;
            $order->total_cogs_price = $cartItem->getCogs() * (float)$cartItem->quantity;
            $order->customer_id = $user->id;
            $order->customer_name = $user->name;
            $order->customer_email = $cartItem->reception_email;
            $order->customer_phone = $user->phone;
            $order->user_ip = $request->userIP;
            $order->status = Order::STATUS_VERIFYING;
            $order->payment_at = date('Y-m-d H:i:s');
            $order->generateAuthKey();

            // Item detail
            $order->game_id = $cartItem->id;
            $order->game_title = $cartItem->getLabel();
            $order->quantity = $cartItem->quantity;
            $order->unit_name = $cartItem->unit_name;
            $order->sub_total_unit = $cartItem->getTotalUnit();
            $order->promotion_unit = $promotionUnit;
            $order->promotion_id = $cart->hasPromotion() ? $cart->getPromotionItem()->id : null;
            $order->total_unit = $cartItem->getTotalUnit() + $promotionUnit;
            $order->username = $cartItem->username;
            $order->password = $cartItem->password;
            $order->platform = $cartItem->platform;
            $order->login_method = $cartItem->login_method;
            $order->character_name = $cartItem->character_name;
            $order->recover_code = $cartItem->recover_code;
            $order->server = $cartItem->server;
            $order->note = $cartItem->note;
            $gateway->setReferenceId($order->auth_key);
            
            if ($reseller) {
                $order->saler_id = $reseller->manager_id;
            } elseif ($cartItem->saler_code && !$order->saler_id) {
                $invitor = User::findOne(['saler_code' => $cartItem->saler_code]);
                $order->saler_id = ($invitor) ? $invitor->id : null;
            }
            if (!$order->save()) throw new BadRequestHttpException("Error Processing Request", 1);
            $order->log(sprintf("Created. Status %s (%s - %s)", $order->status, $identifier, $gateway->type));
            $cart->clear();
            return $gateway->request();
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    public function actionVerify($identifier)
    {
        $gateway = PaymentGatewayFactory::getClient($identifier);
        $gateway->confirm_url = 'cart/verify';
        $gateway->success_url = 'cart/success';
        $gateway->cancel_url = 'cart/cancel';
        $gateway->error_url = 'cart/error';
        $request = Yii::$app->request;
        $refId = $request->get('ref');
        try {
            if ($gateway->confirm()) {
                $user = Yii::$app->user->getIdentity();
                $order = Order::find()->where([
                    'payment_method' => $identifier,
                    'auth_key' => $refId,
                    'status' => Order::STATUS_VERIFYING,
                ])->one();
                if (!$order) throw new \Exception('Order is not exist');
                $order->attachBehavior('log', OrderLogBehavior::className());
                $order->on(Order::EVENT_AFTER_UPDATE, [ShoppingEventHandler::className(), 'sendNotificationEmail']);
                $order->on(Order::EVENT_AFTER_UPDATE, [ShoppingEventHandler::className(), 'applyVoucherForUser']);
                $order->on(Order::EVENT_AFTER_UPDATE, [ShoppingEventHandler::className(), 'applyAffiliateProgram']);
                $order->status = Order::STATUS_PENDING;
                $order->payment_at = date('Y-m-d H:i:s');
                $order->payment_id = $gateway->getPaymentId();
                $order->save();
                $order->log(sprintf("Verified, Status is %s", $order->status));
                return $gateway->doSuccess();
            } else {
                return $gateway->doError();
            }
        } catch (\Exception $e) {
            Yii::error($gateway->identifier . $gateway->getReferenceId() . " confirm error " . $e->getMessage());
            return $gateway->doError();
        }
    }

    public function actionSuccess()
    {
        $this->view->params['main_menu_active'] = 'shop.index';
        $refId = Yii::$app->request->get('ref');
        $order = Order::findOne(['auth_key' => $refId]);
        $gateway = PaymentGatewayFactory::getClient($order->payment_method);
        $user = Yii::$app->user->getIdentity();
        return $this->render('success', [
            'order' => $order,
            'user' => $user,
            'gateway' => $gateway
        ]);
    }

    public function actionCancel()
    {
        return $this->render('/site/error', [           
            'name' => 'Canncel order',
            'message' => 'Your have cancelled order'
        ]);

    }

    public function actionError()
    {
        return $this->render('/site/error', [           
            'name' => 'Error order',
            'message' => 'There is error occurred'
        ]);
    }

    public function actionPaypalCapture()
    {
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        $reseller = $user->reseller;
        $cart = Yii::$app->cart;
        $cartItem = $cart->getItem();
        $totalPrice = $cart->getTotalPrice();
        $subTotalPrice = $cart->getSubTotalPrice();
        $promotionCoin = $cart->getPromotionCoin();
        $promotionUnit = $cart->getPromotionUnit();

        // return $this->asJson(['status' => true, 'post' => $request->post()]);
        if ($request->isPost && $request->isAjax) {
            $data = $request->post();
            $status = ArrayHelper::getValue($data, 'status');

            // Payer information
            $payer = ArrayHelper::getValue($data, 'payer', []);
            $payer_email_address = ArrayHelper::getValue($payer, 'email_address');

            // purchase information
            $purchase_units = ArrayHelper::getValue($data, 'purchase_units', []);
            $purchase_unit = reset($purchase_units);

            // payment information
            $payments = ArrayHelper::getValue($purchase_unit, 'payments', []);
            $captures = ArrayHelper::getValue($payments, 'captures', []);
            $capture = reset($captures);
            $captureId = ArrayHelper::getValue($capture, 'id');

            if (strtoupper($status) != "COMPLETED") return $this->asJson(['status' => false]);

            // Order detail
            $order = new Order();
            $order->attachBehavior('log', OrderLogBehavior::className());
            $order->on(Order::EVENT_AFTER_INSERT, [ShoppingEventHandler::className(), 'sendNotificationEmail']);
            $order->on(Order::EVENT_AFTER_INSERT, [ShoppingEventHandler::className(), 'applyVoucherForUser']);
            $order->on(Order::EVENT_AFTER_INSERT, [ShoppingEventHandler::className(), 'applyAffiliateProgram']);
            $order->payment_method = 'paypal';
            $order->payment_type = 'online';
            $order->price = $cartItem->getPrice();
            $order->cogs_price = $cartItem->getCogs();
            $order->sub_total_price = $subTotalPrice;
            $order->total_discount = $promotionCoin;
            $order->total_price = $totalPrice;
            $order->total_price_by_currency = FormatConverter::convertCurrencyToCny($totalPrice);
            $order->currency = 'USD';
            $order->total_cogs_price = $cartItem->getCogs() * (float)$cartItem->quantity;
            $order->customer_id = $user->id;
            $order->customer_name = $user->name;
            $order->customer_email = $cartItem->reception_email;
            $order->customer_phone = $user->phone;
            $order->user_ip = $request->userIP;
            $order->status = Order::STATUS_PENDING;
            $order->payment_at = date('Y-m-d H:i:s');
            $order->payment_id = $captureId;
            $order->generateAuthKey();

            // Item detail
            $order->game_id = $cartItem->id;
            $order->game_title = $cartItem->getLabel();
            $order->quantity = $cartItem->quantity;
            $order->unit_name = $cartItem->unit_name;
            $order->sub_total_unit = $cartItem->getTotalUnit();
            $order->promotion_unit = $promotionUnit;
            $order->promotion_id = $cart->hasPromotion() ? $cart->getPromotionItem()->id : null;
            $order->total_unit = $cartItem->getTotalUnit() + $promotionUnit;
            $order->username = $cartItem->username;
            $order->password = $cartItem->password;
            $order->platform = $cartItem->platform;
            $order->login_method = $cartItem->login_method;
            $order->character_name = $cartItem->character_name;
            $order->recover_code = $cartItem->recover_code;
            $order->server = $cartItem->server;
            $order->note = $cartItem->note;
            if ($reseller) {
                $order->saler_id = $reseller->manager_id;
            } elseif ($cartItem->saler_code && !$order->saler_id) {
                $invitor = User::findOne(['saler_code' => $cartItem->saler_code]);
                $order->saler_id = ($invitor) ? $invitor->id : null;
            }
            if (!$order->save()) throw new BadRequestHttpException("Error Processing Request", 1);
            $order->log(sprintf("Created and captured by Paypal. Status is %s", $order->id, $order->status));
            $cart->clear();

            $settings = Yii::$app->settings;
            $username = $settings->get('PaypalSettingForm', 'username');
            $password = $settings->get('PaypalSettingForm', 'password');
            if ($username && $password) {
                Yii::error(sprintf("Mail send from %s to %s", $username, $payer_email_address), __METHOD__);
                $fromName = sprintf("%s Administrator", Yii::$app->name);
                $mailer = Yii::createObject([
                    'class' => 'yii\swiftmailer\Mailer',
                    'viewPath' => '@frontend/mail',
                    'transport' => [
                        'class' => 'Swift_SmtpTransport',
                        'host' => 'smtp.gmail.com',
                        'username' => $username,
                        'password' => $password,
                        'port' => '587',
                        'encryption' => 'tls',
                    ],            
                    'useFileTransport' => false,
                ]);
                $mailer->compose('paypal_confirm_mail', ['data' => $data])
                ->setTo($payer_email_address)
                ->setFrom([$username => $fromName])
                ->setSubject(sprintf("AGREEMENT CONFIRMATION - %s / %s", $order->id, $captureId))
                ->setTextBody(sprintf("AGREEMENT CONFIRMATION - %s / %s", $order->id, $captureId))
                ->send();
            }

            return $this->asJson([
                'status' => true, 
                'order' => $order->id, 
                'success_link' => Url::to(['cart/success', 'ref' => $order->auth_key], true),
            ]);
        }
        return $this->asJson([
            'status' => false, 
        ]);
    }
}