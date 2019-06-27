<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use frontend\forms\FetchProductForm;
use frontend\models\AddCartForm;
use frontend\models\Product;
use common\models\Order;
use common\models\OrderItems;
use common\models\OrderFee;
use frontend\components\cart\CartItem;
use frontend\components\cart\CartDiscount;
use common\models\UserWallet;

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
        $item = $cart->getItem();
        if (!$item) return $this->redirect(['site/index']);
        $item->setScenario($request->post('scenario'));
        $discount = $cart->hasDiscount() ? $cart->getDiscountItem() : new CartDiscount();   
        if ($request->isPost) {
            if ($item->load($request->post()) && $item->validate()) {
                $cart->add($item);
                if ($item->scenario == CartItem::SCENARIO_EDIT) {
                    $discount->load($request->post());
                    $discount->user_id = Yii::$app->user->id;
                    $discount->game_ids = [$item->game_id];
                    if (!$discount->validate() || !$discount->code) $cart->removeDiscountItem();    
                    else $cart->setDiscountItem($discount);
                } elseif ($item->scenario == CartItem::SCENARIO_INFO) {
                    return $this->redirect(Url::to(['cart/checkout']));
                } 
            }
        }

        return $this->render('index', [
            'cart' => $cart,
            'discount' => $discount,
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
        $item = new CartItem(['game_id' => $id]);
        $item->setScenario(CartItem::SCENARIO_ADD);
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
        $totalPrice = $cart->getTotalPrice();
        $subTotalPrice = $cart->getSubTotalPrice();
        $discount = $cart->getTotalDiscount();
        $cartItem = $cart->getItem();

        // Order detail
        $order = new Order();
        $order->sub_total_price = $subTotalPrice;
        $order->total_discount = $discount;
        $order->total_price = $totalPrice;
        $order->customer_id = $user->id;
        $order->customer_name = $user->name;
        $order->customer_email = $user->email;
        $order->customer_phone = $user->phone;
        $order->status = Order::STATUS_PENDING;
        $order->saler_id = $user->invited_by;
        $order->payment_at = date('Y-m-d H:i:s');
        $order->generateAuthKey();

        // Item detail
        $order->game_id = $cartItem->getGame()->id;
        $order->game_title = $cartItem->getLabel();
        $order->quantity = $cartItem->quantity;
        $order->unit_name = $cartItem->getUnitName();
        $order->total_unit = $cartItem->getTotalPack();
        $order->username = $cartItem->username;
        $order->password = $cartItem->password;
        $order->platform = $cartItem->platform;
        $order->login_method = $cartItem->login_method;
        $order->character_name = $cartItem->character_name;
        $order->recover_code = $cartItem->recover_code;
        $order->server = $cartItem->server;
        $order->note = $cartItem->note;

        if (!$order->save()) throw new BadRequestHttpException("Error Processing Request", 1);

        if ($cart->hasDiscount()) {
            $discountItem = $cart->getDiscountItem();
            $itemFee = new OrderFee();
            $itemFee->order_id = $order->id;
            $itemFee->type = OrderFee::TYPE_DISCOUNT;
            $itemFee->description = $discountItem->code;
            $itemFee->reference = $discountItem->getPromotion()->id;
            $itemFee->amount = $discountItem->getPrice();
            $itemFee->save();
        }

        $wallet = new UserWallet();
        $wallet->coin = (-1) * $totalPrice;
        $wallet->balance = $user->getWalletAmount() + $wallet->coin;
        $wallet->type = UserWallet::TYPE_OUTPUT;
        $wallet->description = "Pay for order #$order->auth_key";
        $wallet->ref_name = Order::classname();
        $wallet->ref_key = $order->auth_key;
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
        $this->layout = 'notice';
        return $this->render('/site/notice', [           
            'title' => 'Place order successfully',
            'content' => 'Congratulation.'
        ]);
    }
}