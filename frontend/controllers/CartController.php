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
use frontend\components\cart\CartItem;
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
                'only' => ['index', 'add', 'checkout', 'purchase'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['add'],
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
                    'purchase' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $id = $request->get('pid');
        $quantity = $request->get('qt', 1);
        $product = Product::findOne($id);
        if (!$product) throw new BadRequestHttpException('Không tìm thấy sản phẩm');
        $game = $product->game;

        $item = new CartItem([
            'id' => $id,
            'quantity' => $quantity
        ]);
        return $this->render('index', [
            'game' => $game,
            'item' => $item,
            'quantity' => $quantity
        ]);
    }

    public function actionAdd()
    {
    	$request = Yii::$app->request;
    	if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
    	if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => null, 'errors' => []]);

        $cart = Yii::$app->cart;
        $cart->clear();
        $item = new CartItem();
        if ($item->load($request->post()) && $item->validate()) {
            $cart->add($item);
            return json_encode(['status' => true, 'user_id' => Yii::$app->user->id, 'cart' => $cart->getItems()]);
        } else {
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $item->getErrors()]);
        }

    }

    public function actionCheckout()
    {
        $model = new CartItem();
        $user = Yii::$app->user->getIdentity();
        $cart = Yii::$app->cart;
        $canPlaceOrder = $user->getWalletAmount() > $cart->getTotalPrice();
        return $this->render('checkout', ['model' => $model, 'can_place_order' => $canPlaceOrder]);
    }

    public function actionPurchase()
    {
        // Create order
        $user = Yii::$app->user->getIdentity();
        $cart = Yii::$app->cart;
        $totalPrice = $cart->getTotalPrice();

        $order = new Order();
        $order->total_price = $totalPrice;
        $order->customer_id = $user->id;
        $order->customer_name = $user->name;
        $order->customer_email = $user->email;
        $order->customer_phone = $user->phone;
        $order->status = Order::STATUS_PENDING;
        $order->generateAuthKey();
        if (!$order->save()) throw new BadRequestHttpException("Error Processing Request", 1);

        foreach ($cart->getItems() as $cartItem) {
            $item = new OrderItems();
            $item->item_title = $cartItem->getLabel();
            $item->type = OrderItems::TYPE_PRODUCT;
            $item->order_id = $order->id;
            $item->game_id = $cartItem->getGameId();
            $item->product_id = $cartItem->getUniqueId();
            $item->price = $cartItem->getPrice();
            $item->quantity = $cartItem->quantity;
            $item->total = $cartItem->getTotalPrice();
            $item->unit_name = $cartItem->getUnitName();
            $item->unit = $cartItem->getUnitGame();
            $item->total_unit = $cartItem->getTotalUnitGame();
            $item->username = $cartItem->username;
            $item->password = $cartItem->password;
            $item->platform = $cartItem->platform;
            $item->login_method = $cartItem->login_method;
            $item->character_name = $cartItem->character_name;
            $item->recover_code = $cartItem->recover_code;
            $item->server = $cartItem->server;
            $item->note = $cartItem->note;
            $item->save();
        }

        $wallet = new UserWallet();
        $wallet->coin = $totalPrice;
        $wallet->type = UserWallet::TYPE_OUTPUT;
        $wallet->description = "Pay for order #$order->auth_key";
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
            'title' => 'Đặt hàng thành công',
            'content' => 'Xin chúc mừng bạn đã đặt hàng thành công'
        ]);
    }
}