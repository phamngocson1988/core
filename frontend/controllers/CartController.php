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



use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Api\ItemList;

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
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $model->getErrors()]);
        }

    }

    public function actionCheckout()
    {
        $model = new CartItem();
        return $this->render('checkout', ['model' => $model]);
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
        $order->generateAuthKey();

        if (!$order->save()) throw new BadRequestHttpException("Error Processing Request", 1);

        // Create order item
        $itemList = [];
        foreach ($cart->getItems() as $cartItem) {
            $item = new OrderItems();
            $item->item_title = $cartItem->getLabel();
            $item->type = OrderItems::TYPE_PRODUCT;
            $item->order_id = $order->id;
            $item->product_id = $cartItem->getUniqueId();
            $item->price = $cartItem->getPrice();
            $item->quantity = $cartItem->quantity;
            $item->total = $cartItem->getTotalPrice();
            $item->unit_name = $cartItem->getUnitName();
            $item->unit = $cartItem->getUnitGame();
            $item->total_unit = $cartItem->getTotalUnitGame();
            $item->username = $cartItem->username;
            $item->password = $cartItem->password;
            $item->character_name = $cartItem->character_name;
            $item->recover_code = $cartItem->recover_code;
            $item->server = $cartItem->server;
            $item->note = $cartItem->note;

            // Config item list for paypal
            $itemList[] = [
                'name' => $cartItem->getLabel(),
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->getPrice(),
                'currency' => 'USD'
            ];
        }

        // Send to paypal
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AQK-NCCq492D7OEICMTiFzyWPskls32NEhwZ9t7eERBk2kHuhjywMFA8BjMkj1XqFvQTtok6Srs1R-OF',     // ClientID
                'EBmAgMX7piQWJu1gkuCbmIRW3MJ1pv-cdYbsxmKj6-esCGhGwCoQ4e-eoQu0d7MCHJxrMKSlY81RFvjx'      // ClientSecret
            )
        );

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($totalPrice);
        $amount->setCurrency('USD');
        $ppItemList = new ItemList($itemList);

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription("Pay for order " . $order->id);
        $transaction->setInvoiceNumber($order->id);
        $transaction->setCustom($order->auth_key);

        $transaction->setItemList($ppItemList);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl(Url::to(['cart/success'], true))
            ->setCancelUrl(Url::to(['cart/error'], true));

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);

        // 4. Make a Create Call and print the values
        try {
            $payment->create($apiContext);
            if ('created' == strtolower($payment->state)) {// order was created
                $order->payment_id = $payment->id;
                $order->payment_method = $payer->getPaymentMethod();
                $order->save();
                $cart->clear();
                return $this->redirect($payment->getApprovalLink());
            }  
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $order->delete();
            echo $ex->getData();
        }
    }

    public function actionSuccess()
    {
        $request = Yii::$app->request;
        $paymentId = $request->get('paymentId');
        $order = Order::findOne(['payment_id' => $paymentId]);

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AQK-NCCq492D7OEICMTiFzyWPskls32NEhwZ9t7eERBk2kHuhjywMFA8BjMkj1XqFvQTtok6Srs1R-OF',     // ClientID
                'EBmAgMX7piQWJu1gkuCbmIRW3MJ1pv-cdYbsxmKj6-esCGhGwCoQ4e-eoQu0d7MCHJxrMKSlY81RFvjx'      // ClientSecret
            )
        );
        $payment = Payment::get($paymentId, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($_GET['PayerID']);

        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();

        $details->setShipping(0)->setTax(0)->setSubtotal(0);

        $amount->setCurrency('USD');
        $amount->setTotal($order->total_price);
        $amount->setDetails($details);
        $transaction->setAmount($amount);

        // $execution->addTransaction($transaction);

        try {
            $result = $payment->execute($execution, $apiContext);

            try {
                $payment = Payment::get($paymentId, $apiContext);
            } catch (Exception $ex) {
                $order->delete();
                exit(1);
            }
        } catch (Exception $ex) {
            exit(1);
        }







echo '</pre>';

        $order = Order::findOne(['payment_id' => $paymentId]);
        $paymentData = json_encode($payment);
        $order->payment_data = $paymentData;
        $order->payment_at = date('Y-m-d H:i:s');
        $order->status = Order::STATUS_PROCESSING;
        $order->save();
        die;
    }

    public function actionError()
    {
        die;
    }
}