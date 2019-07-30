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
use frontend\forms\PurchaseGameForm;
use frontend\events\ShoppingEventHandler;

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

    public function actions()
    {
        return [
            'kinggems' => [
                'class' => 'frontend\components\payment\actions\KinggemsPaygateAction',
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
        if (!$identifier) throw new InvalidParamException('You must choose a payment gateway');
        $paymentCart = new PaymentCart([
            'title' => 'Pay for buying Game',
        ]);
        $cart = Yii::$app->cart;
        if (!$cart->getItems()) return $this->redirect(['site/index']);
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

        // Create order
        $totalPrice = $cart->getTotalPrice();
        $subTotalPrice = $cart->getSubTotalPrice();
        $promotionCoin = $cart->getPromotionCoin();
        $promotionUnit = $cart->getPromotionUnit();

        // Order detail
        $order = new Order();
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
        $cart->clear();
        $gateway->setCart($paymentCart);
        $paygateData = $gateway->request();
        try {
            return $this->render($identifier, [
                'paygateData' => (array)$paygateData,
                'transaction' => $trn 
            ]);
        } catch (ViewNotFoundException $e) {
            return;
        }
    }

    public function actionPurchase1()
    {
        // Create order
        $user = Yii::$app->user->getIdentity();
        $cart = Yii::$app->cart;
        $form = new PurchaseGameForm([
            'user' => $user,
            'cart' => $cart
        ]);
        $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'sendNotificationEmail']);
        $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'applyVoucherForUser']);
        $form->on(PurchaseGameForm::EVENT_AFTER_PURCHASE, [ShoppingEventHandler::className(), 'applyAffiliateProgram']);
        if (!$form->purchase()) {
            print_r($form->getErrorSummary(true));die;
        } else {
            $cart->clear();
        }
        return $this->render('/site/notice', [           
            'title' => 'Place order successfully',
            'content' => 'Congratulation.'
        ]);
    }
}