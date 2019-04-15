<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\PricingCoin;
use common\models\UserWallet;
use common\models\Transaction;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use frontend\components\cart\CartDiscount;
use frontend\components\cart\CartPricingItem;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction as PaypalTransaction;
use PayPal\Api\PaymentExecution;

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
                        'actions' => ['purchase', 'success', 'store', 'add'],
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

    public function actionIndex()
    {
        $models = PricingCoin::find()->select('id')->all();
        $items = array_map(function($model){
            return new CartPricingItem(['pricing_id' => $model->id, 'quantity' => 1]);
        }, $models);
    	return $this->render('index', [
            'items' => $items,
    	]);
    }

    public function actionAdd()
    {
    	$request = Yii::$app->request;
    	if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
    	if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => null, 'errors' => []]);

        $cart = Yii::$app->cart->setMode('pricing');
        $cart->clear();
        $item = new CartPricingItem(['scenario' => CartPricingItem::SCENARIO_ADD]);
        if ($item->load($request->post()) && $item->validate()) {
            $cart->add($item);
            return json_encode(['status' => true, 'user_id' => Yii::$app->user->id, 'cart' => $cart->getItems()]);
        } else {
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $item->getErrors()]);
        }

    }

    public function actionConfirm()
    {
        $request = Yii::$app->request;
        $cart = Yii::$app->cart->setMode('pricing');
        $item = $cart->getItem();
        if (!$item) return $this->redirect(['site/index']);

        $item->setScenario(CartPricingItem::SCENARIO_EDIT);
        $discount = $cart->getDiscount();

        if ($request->isPost) {
            if ($item->load($request->post()) && $item->validate()) {
                $cart->add($item);
            }
            
            if ($discount) $cart->remove($discount->getUniqueId());
            $discount = new CartDiscount();
            $discount->setCart($cart);
            if ($discount->load($request->post()) && $discount->validate()) {
                $cart->add($discount);
            }
        }
        if (!$discount) $discount = new CartDiscount();

        return $this->render('confirm', [
            'discount' => $discount,
            'item' => $item,
        ]);
    }

    public function actionCheckout()
    {

        $cart = Yii::$app->cart->setMode('pricing');
        if (!$cart->getItem()) throw new NotFoundHttpException("You have not added any pricing package", 1);
        return $this->render('checkout');
    }

    public function actionPurchase()
    {
        // $session = Yii::$app->session;
        // $cart = $session->has(self::PRICING_CART) ? $session->get(self::PRICING_CART) : [];
        // $chosenId = ArrayHelper::getValue($cart, 'id');
        // $chosenQuantity = ArrayHelper::getValue($cart, 'quantity', 1);
        // if (!$chosenId) throw new NotFoundHttpException("You have not added any pricing package", 1);
        // $pricing = PricingCoin::findOne($chosenId); 
        // if (!$pricing || !$pricing->isVisible()) throw new NotFoundHttpException("The package is not found", 1);

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
        $clientId = 'AQK-NCCq492D7OEICMTiFzyWPskls32NEhwZ9t7eERBk2kHuhjywMFA8BjMkj1XqFvQTtok6Srs1R-OF';
        $clientSecret = 'EBmAgMX7piQWJu1gkuCbmIRW3MJ1pv-cdYbsxmKj6-esCGhGwCoQ4e-eoQu0d7MCHJxrMKSlY81RFvjx';
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                // 'AQK-NCCq492D7OEICMTiFzyWPskls32NEhwZ9t7eERBk2kHuhjywMFA8BjMkj1XqFvQTtok6Srs1R-OF',     // ClientID
                // 'EBmAgMX7piQWJu1gkuCbmIRW3MJ1pv-cdYbsxmKj6-esCGhGwCoQ4e-eoQu0d7MCHJxrMKSlY81RFvjx'      // ClientSecret
                $clientId,
                $clientSecret
            )
        );

        $cart = Yii::$app->cart->setMode('pricing');
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
            $discount = $cart->getDiscount();
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
            ->setDescription("Pay for package of coins #" . $cartItem->getUniqueId() . " " . $cartItem->getPricing()->title)
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
            	// $session = Yii::$app->session;
            	// $session->set('payment_method', 'paypal');
            	// $session->set('payment_id', $payment->id);
             //    $session->set('package_id', $cartItem->getUniqueId());
            	// $session->set('package_quantity', $cartItem->quantity);
                return $this->redirect($payment->getApprovalLink());
            }  
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getData();
        }
    }

    public function actionSuccess()
    {
        $request = Yii::$app->request;
        $cart = Yii::$app->cart->setMode('pricing');
        $cartItem = $cart->getItem();
        $user = Yii::$app->user->getIdentity();
        $paymentId = $request->get('paymentId');
        $payerId = $request->get('PayerID');
        $token = $request->get('token');

        if (!$paymentId || !$payerId || !$token) throw new BadRequestHttpException("The request is invalid", 1);

        // $settings = Yii::$app->settings;
        // $paypalMode = $settings->get('PaypalSettingForm', 'mode', 'sandbox');
        // if ($paypalMode == 'live') {
        //     $clientId = $settings->get('PaypalSettingForm', 'client_id');
        //     $clientSecret = $settings->get('PaypalSettingForm', 'client_secret');
        // } else {
        //     $clientId = $settings->get('PaypalSettingForm', 'sandbox_client_id');
        //     $clientSecret = $settings->get('PaypalSettingForm', 'sandbox_client_secret');
        // }
        $clientId = 'AQK-NCCq492D7OEICMTiFzyWPskls32NEhwZ9t7eERBk2kHuhjywMFA8BjMkj1XqFvQTtok6Srs1R-OF';
        $clientSecret = 'EBmAgMX7piQWJu1gkuCbmIRW3MJ1pv-cdYbsxmKj6-esCGhGwCoQ4e-eoQu0d7MCHJxrMKSlY81RFvjx';
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
            	// Create transaction
            	$trn = new Transaction();
                $trn->user_id = $user->id;
                $trn->payment_method = 'paypal';
                $trn->payment_id = $paymentId;
                $trn->payment_data = $token;
                $trn->amount = $transaction->getAmount()->getTotal();
                $trn->description = "Paypal #$paymentId";
                $trn->created_by = $user->id;
                $trn->status = Transaction::STATUS_COMPLETED;
                $trn->payment_at = date('Y-m-d H:i:s');
                $trn->generateAuthKey();
                $trn->save();

                $wallet = new UserWallet();
                $wallet->coin = $cartItem->getPricing()->num_of_coin * $cartItem->quantity;
                $wallet->type = UserWallet::TYPE_INPUT;
                $wallet->description = "Transaction #$trn->auth_key";
                $wallet->created_by = $user->id;
                $wallet->user_id = $user->id;
                $wallet->status = UserWallet::STATUS_COMPLETED;
                $wallet->payment_at = date('Y-m-d H:i:s');
                $wallet->save();
            }
        } catch (Exception $ex) {
        	// $session->remove('payment_method');
	        // $session->remove('payment_id');
         //    $session->remove('package_id');
	        // $session->remove('package_quantity');
            exit(1);
        }

        // $session->remove('payment_method');
        // $session->remove('payment_id');
        // $session->remove('package_id');
        // $session->remove('package_quantity');
        $this->layout = 'notice';
        return $this->render('/site/notice', [
            'title' => 'You have just bought a pricing successfully.',
            'content' => 'Congratulations!!! Now your wallet is full of King Coins.'
        ]);
    }

    public function actionError()
    {
        $request = Yii::$app->request;
        $token = $request->get('token');
        throw new BadRequestHttpException("You have just cancelled the order", 1);
    }
}