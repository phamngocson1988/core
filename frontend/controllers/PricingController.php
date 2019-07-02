<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\PricingCoin;
use common\models\UserWallet;
use common\models\PaymentTransaction;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use frontend\components\kingcoin\CartDiscount;
use frontend\components\kingcoin\CartItem;


use frontend\components\payment\cart\PaymentItem;
use frontend\components\payment\cart\PaymentCart;
use frontend\components\payment\cart\PaymentDiscount;
use frontend\components\payment\PaymentGateway;
use frontend\components\payment\PaymentGatewayFactory;

/**
 * UserController
 */
class PricingController extends Controller
{
    const PRICING_CART = 'pricing_cart';

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['purchase', 'success', 'store'],
                'rules' => [
                    [
                        'actions' => ['purchase', 'success', 'store', 'add', 'verify'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'store' => ['post'],
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
        $models = PricingCoin::find()->select('id')->all();
        $items = array_map(function($model){
            return new CartItem(['pricing_id' => $model->id, 'quantity' => 1]);
        }, $models);
        if ($request->isPost) {
            $item = new CartItem();
            $item->load($request->post());
            $items = array_filter($items, function($model) use ($item) {
                return $model->pricing_id == $item->pricing_id;
            });
        }
    	return $this->render('index', [
            'items' => $items,
    	]);
    }

    public function actionAdd($id)
    {
    	$request = Yii::$app->request;
    	if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
    	if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => null, 'errors' => []]);

        $cart = Yii::$app->kingcoin;
        $cart->clear();
        $item = new CartItem(['pricing_id' => $id]);
        $item->setScenario(CartItem::SCENARIO_ADD);
        if ($item->load($request->post()) && $item->validate()) {
            $cart->add($item);
            return json_encode(['status' => true, 'user_id' => Yii::$app->user->id, 'checkout_url' => Url::to(['pricing/confirm'])]);
        } else {
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $item->getErrors()]);
        }
    }

    public function actionConfirm()
    {
        $request = Yii::$app->request;
        $cart = Yii::$app->kingcoin;
        $item = $cart->getItem();
        if (!$item) return $this->redirect(['site/index']);
        $item->setScenario(CartItem::SCENARIO_EDIT);
        $discount = $cart->hasDiscount() ? $cart->getDiscountItem() : new CartDiscount();
        if ($item->load($request->post()) && $item->validate()) {
            $cart->clear();
            $cart->add($item);
        }
        if ($request->isPost && $discount->load($request->post())) {
            if (!$discount->validate() || !$discount->code) $cart->removeDiscountItem();
            else $cart->setDiscountItem($discount);
        }

        return $this->render('confirm', [
            'discount' => $discount,
            'cart' => $cart,
        ]);
    }

    public function actionCheckout()
    {

        $cart = Yii::$app->kingcoin;
        if (!$cart->getItem()) throw new NotFoundHttpException("You have not added any pricing package", 1);
        return $this->render('checkout');
    }

    public function actionPurchase()
    {
        $request = Yii::$app->request;
        $identifier = $request->post('identifier');
        if (!$identifier) throw new InvalidParamException('You must choose a payment gateway');
        $paymentCart = new PaymentCart([
            'title' => 'Test cho vui',
        ]);
        $cart = Yii::$app->kingcoin;
        $cartItem = $cart->getItem();
        $paymentCartItem = new PaymentItem([
            'id' => $cartItem->getUniqueId(),
            'title' => $cartItem->getLabel(),
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->getPrice()
        ]);
        $paymentCart->addItem($paymentCartItem);
        
        if ($cart->getTotalDiscount()) {
            $discountItem = $cart->getDiscountItem();
            $paymentDiscount = new PaymentDiscount([
                'id' => $discountItem->code,
                'title' => 'Discount promotion code ' . $discountItem->code,
                'price' => $discountItem->getPrice()
            ]);
            $paymentCart->setDiscount($paymentDiscount);
        }
        $gateway = PaymentGatewayFactory::getClient($identifier);
        $gateway->setCart($paymentCart);
        $gateway->on(PaymentGateway::EVENT_BEFORE_REQUEST, function($event) { 
            $gateway = $event->sender;
            $cart = Yii::$app->kingcoin;
            $cartItem = $cart->getItem();
            $totalPrice = $cart->getTotalPrice();
            $subTotalPrice = $cart->getSubTotalPrice();
            $totalCoin = $cartItem->getPricing()->num_of_coin * $cartItem->quantity;
            $user = Yii::$app->user->getIdentity();
            
            $trn = new PaymentTransaction();
            $trn->user_id = $user->id;
            $trn->payment_method = $gateway->identifier;
            $trn->payment_id = $gateway->getReferenceId();
            $trn->price = $subTotalPrice;
            $trn->total_price = $totalPrice;
            $trn->coin = $totalCoin;
            $trn->discount_coin = 0;
            $trn->total_coin = $totalCoin;
            $trn->description = $gateway->identifier;
            $trn->created_by = $user->id;
            $trn->status = PaymentTransaction::STATUS_PENDING;
            $trn->payment_at = date('Y-m-d H:i:s');
            $trn->generateAuthKey();
            if ($cart->getTotalDiscount()) {
                $discount = $cart->getDiscountItem();
                $trn->discount_price = $cart->getTotalDiscount();
                $trn->discount_code = $discount->getPromotion()->code;
            }
            $trn->save();
            $cart->clear();
        });
        return $gateway->request();
    }

    public function actionVerify($identifier)
    {
        $gateway = PaymentGatewayFactory::getClient($identifier);
        $gateway->on(PaymentGateway::EVENT_CONFIRM_SUCCESS, function($event) {
            try {
                $gateway = $event->sender;
                $user = Yii::$app->user->getIdentity();
                $refId = $gateway->getReferenceId();
                $trn = PaymentTransaction::find()->where([
                    'payment_id' => $refId, 
                    'status' => PaymentTransaction::STATUS_PENDING
                ])->one();
                if (!$trn) {
                    Yii::$app->session->setFlash('error', 'Đã có lỗi xảy ra');
                    throw new Exception("Không tìm thấy giao dịch $refId");
                }
                $trn->status = PaymentTransaction::STATUS_COMPLETED;
                $trn->payment_at = date('Y-m-d H:i:s');
                $trn->save();

                $wallet = new UserWallet();
                $wallet->coin = $trn->total_coin;
                $wallet->balance = $user->getWalletAmount() + $wallet->coin;
                $wallet->type = UserWallet::TYPE_INPUT;
                $wallet->description = "Transaction #$trn->auth_key";
                $wallet->ref_name = PaymentTransaction::className();
                $wallet->ref_key = $trn->auth_key;
                $wallet->created_by = $user->id;
                $wallet->user_id = $user->id;
                $wallet->status = UserWallet::STATUS_COMPLETED;
                $wallet->payment_at = date('Y-m-d H:i:s');
                $wallet->save();
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), 1);
            }
        });
        return $gateway->confirm();
    }

    public function actionSuccess()
    {
        $this->layout = 'notice';
        return $this->render('/site/notice', [
            'title' => 'You have just bought a pricing successfully.',
            'content' => 'Congratulations!!! Now your wallet is full of King Coins.'
        ]);
    }

    public function actionCancel($identifier)
    {
        $gateway = PaymentGatewayFactory::getClient($identifier);
        $gateway->on(PaymentGateway::EVENT_AFTER_CANCEL, function($event) {
            try {
                $gateway = $event->sender;
                $refId = $gateway->getReferenceId();
                $trn = PaymentTransaction::find()->where([
                    'payment_id' => $refId, 
                    'status' => PaymentTransaction::STATUS_PENDING
                ])->one();
                if ($trn) $trn->delete();
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), 1);
            }
        });
        $gateway->cancel();
        throw new BadRequestHttpException("You have just cancelled the order", 1);
    }

    public function actionPurchase1()
    {
        // Send to paypal
        $settings = Yii::$app->settings;
        $paypalMode = $settings->get('PaypalSettingForm', 'mode', 'sandbox');
        if ($paypalMode == 'live') {
            $clientId = $settings->get('PaypalSettingForm', 'client_id');
            $clientSecret = $settings->get('PaypalSettingForm', 'client_secret');
        } else {
            $clientId = $settings->get('PaypalSettingForm', 'sandbox_client_id');
            $clientSecret = $settings->get('PaypalSettingForm', 'sandbox_client_secret');
        }
        // $clientId = 'AQK-NCCq492D7OEICMTiFzyWPskls32NEhwZ9t7eERBk2kHuhjywMFA8BjMkj1XqFvQTtok6Srs1R-OF';
        // $clientSecret = 'EBmAgMX7piQWJu1gkuCbmIRW3MJ1pv-cdYbsxmKj6-esCGhGwCoQ4e-eoQu0d7MCHJxrMKSlY81RFvjx';
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret)
        );

        $cart = Yii::$app->kingcoin;
        $totalPrice = $cart->getTotalPrice();
        $subTotalPrice = $cart->getSubTotalPrice();
        $cartItem = $cart->getItem();

        $ppItem = new Item();
        $ppItem->setName($cartItem->getPricing()->title)
        ->setCurrency('USD')
        ->setQuantity($cartItem->quantity)
        ->setSku($cartItem->getUniqueId())
        ->setPrice($cartItem->getPrice());
        $itemList[] = $ppItem;

        // For discount
        if ($cart->getTotalDiscount()) {
            $discount = $cart->getDiscountItem();
            $discountItem = new Item();
            $discountItem->setName($discount->getPromotion()->title)
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setSku($discount->code)
            ->setPrice(($cart->getTotalDiscount()) * (-1));
            $itemList[] = $discountItem;
        }

        $ppitemList = new ItemList();
        $ppitemList->setItems($itemList);

        $details = new Details();
        $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal($totalPrice);
        // ### Amount
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($totalPrice)
            ->setDetails($details);

        $transaction = new PaypalTransaction();
        $transaction->setAmount($amount)
            ->setItemList($ppitemList)
            ->setDescription("Pay for package of coins #" . $cartItem->getUniqueId() . " " . $cartItem->getLabel())
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(Url::to(['pricing/success'], true))
            ->setCancelUrl(Url::to(['pricing/error'], true));

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($apiContext);
            if ('created' == strtolower($payment->state)) {// order was created
                return $this->redirect($payment->getApprovalLink());
            }  
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getData();
        }
    }

    public function actionSuccess1()
    {
        $request = Yii::$app->request;
        $cart = Yii::$app->kingcoin;
        $cartItem = $cart->getItem();
        $user = Yii::$app->user->getIdentity();
        $paymentId = $request->get('paymentId');
        $payerId = $request->get('PayerID');
        $token = $request->get('token');

        if (!$paymentId || !$payerId || !$token) throw new BadRequestHttpException("The request is invalid", 1);

        $settings = Yii::$app->settings;
        $paypalMode = $settings->get('PaypalSettingForm', 'mode', 'sandbox');
        if ($paypalMode == 'live') {
            $clientId = $settings->get('PaypalSettingForm', 'client_id');
            $clientSecret = $settings->get('PaypalSettingForm', 'client_secret');
        } else {
            $clientId = $settings->get('PaypalSettingForm', 'sandbox_client_id');
            $clientSecret = $settings->get('PaypalSettingForm', 'sandbox_client_secret');
        }
        // $clientId = 'AQK-NCCq492D7OEICMTiFzyWPskls32NEhwZ9t7eERBk2kHuhjywMFA8BjMkj1XqFvQTtok6Srs1R-OF';
        // $clientSecret = 'EBmAgMX7piQWJu1gkuCbmIRW3MJ1pv-cdYbsxmKj6-esCGhGwCoQ4e-eoQu0d7MCHJxrMKSlY81RFvjx';
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret)
        );
        $payment = Payment::get($paymentId, $apiContext);
        if ('created' != strtolower($payment->state)) throw new BadRequestHttpException("Transaction #$paymentId : status is invalid", 1);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        $transactions = $payment->getTransactions();
        $transaction = reset($transactions);
        $execution->addTransaction($transaction);
        try {
            $payment->execute($execution, $apiContext);
            if ('approved' == strtolower($payment->state)) {// order was created
                $totalPrice = $cart->getTotalPrice();
                $subTotalPrice = $cart->getSubTotalPrice();
                $totalCoin = $cartItem->getPricing()->num_of_coin * $cartItem->quantity;
                
            	// Create transaction
            	$trn = new PaymentTransaction();
                $trn->user_id = $user->id;
                $trn->payment_method = 'paypal';
                $trn->payment_id = $paymentId;
                $trn->payment_data = $token;
                $trn->price = $subTotalPrice;
                $trn->total_price = $totalPrice;//$transaction->getAmount()->getTotal();
                $trn->coin = $totalCoin;
                $trn->discount_coin = 0;
                $trn->total_coin = $totalCoin;
                $trn->description = "Paypal #$paymentId";
                $trn->created_by = $user->id;
                $trn->status = PaymentTransaction::STATUS_COMPLETED;
                $trn->payment_at = date('Y-m-d H:i:s');
                $trn->generateAuthKey();
                if ($cart->getTotalDiscount()) {
                    $discount = $cart->getDiscountItem();
                    $trn->discount_price = $cart->getTotalDiscount();
                    $trn->discount_code = $discount->getPromotion()->code;
                }
                $trn->save();

                $wallet = new UserWallet();
                $wallet->coin = $cartItem->getPricing()->num_of_coin * $cartItem->quantity;
                $wallet->balance = $user->getWalletAmount() + $wallet->coin;
                $wallet->type = UserWallet::TYPE_INPUT;
                $wallet->description = "Transaction #$trn->auth_key";
                $wallet->ref_name = PaymentTransaction::className();
                $wallet->ref_key = $trn->auth_key;
                $wallet->created_by = $user->id;
                $wallet->user_id = $user->id;
                $wallet->status = UserWallet::STATUS_COMPLETED;
                $wallet->payment_at = date('Y-m-d H:i:s');
                $wallet->save();
            }
        } catch (Exception $ex) {
            exit(1);
        }

        $this->layout = 'notice';
        return $this->render('/site/notice', [
            'title' => 'You have just bought a pricing successfully.',
            'content' => 'Congratulations!!! Now your wallet is full of King Coins.'
        ]);
    }

    public function actionError1()
    {
        $request = Yii::$app->request;
        $token = $request->get('token');
        throw new BadRequestHttpException("You have just cancelled the order", 1);
    }
}