<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;

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
        $user = Yii::$app->user->getIdentity();
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
        $cart = Yii::$app->cart;
        $user = Yii::$app->user->getIdentity();
        $canPlaceOrder = $user->getWalletAmount() > $cart->getTotalPrice();
        return $this->render('checkout', ['can_place_order' => $canPlaceOrder]);
    }

    public function actionPurchase()
    {
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
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
            $order->payment_method = $identifier;
            $order->payment_type = $gateway->type;
            // $order->payment_id = $gateway->getReferenceId();
            $order->sub_total_price = $subTotalPrice;
            $order->total_discount = $promotionCoin;
            $order->total_price = $totalPrice;
            $order->customer_id = $user->id;
            $order->customer_name = $user->name;
            $order->customer_email = $cartItem->reception_email;
            $order->customer_phone = $user->phone;
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
            
            if ($cartItem->saler_code) {
                $invitor = User::findOne(['saler_code' => $cartItem->saler_code]);
                $order->saler_id = ($invitor) ? $invitor->id : null;
            }
            if (!$order->save()) throw new BadRequestHttpException("Error Processing Request", 1);
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
                $order->on(Order::EVENT_AFTER_UPDATE, [ShoppingEventHandler::className(), 'sendNotificationEmail']);
                $order->on(Order::EVENT_AFTER_UPDATE, [ShoppingEventHandler::className(), 'applyVoucherForUser']);
                $order->on(Order::EVENT_AFTER_UPDATE, [ShoppingEventHandler::className(), 'applyAffiliateProgram']);
                $order->status = Order::STATUS_PENDING;
                $order->payment_at = date('Y-m-d H:i:s');
                $order->payment_id = $gateway->getPaymentId();
                $order->save();
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

    // public function actionPurchase1()
    // {
    //     // Create order
    //     $user = Yii::$app->user->getIdentity();
    //     $cart = Yii::$app->cart;
    //     $form = new PurchaseGameForm([
    //         'user' => $user,
    //         'cart' => $cart
    //     ]);
    //     $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'sendNotificationEmail']);
    //     $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'applyVoucherForUser']);
    //     $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'applyAffiliateProgram']);
    //     if (!$form->purchase()) {
    //         print_r($form->getErrorSummary(true));die;
    //     } else {
    //         $cart->clear();
    //     }
    //     return $this->render('/site/notice', [           
    //         'title' => 'Place order successfully',
    //         'content' => 'Congratulation.'
    //     ]);
    // }
}