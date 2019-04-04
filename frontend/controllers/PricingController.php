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
                        'actions' => ['purchase', 'success', 'store'],
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
        $session = Yii::$app->session;
        $cart = $session->has(self::PRICING_CART) ? $session->get(self::PRICING_CART) : [];
        $chosenId = ArrayHelper::getValue($cart, 'id');
        $chosenQuantity = ArrayHelper::getValue($cart, 'quantity', 1);
    	$models = PricingCoin::find()->all();
    	return $this->render('index', [
    		'models' => $models,
            'chosen_id' => $chosenId,
            'chosen_quantity' => $chosenQuantity
    	]);
    }

    public function actionStore()
    {
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $id = $request->post('id');
        $qt = $request->post('qt');
        $session->set(self::PRICING_CART, ['id' => $id, 'quantity' => $qt]);
        return json_encode(['status' => true]);
    }

    public function actionConfirm()
    {
        $session = Yii::$app->session;
        $cart = $session->has(self::PRICING_CART) ? $session->get(self::PRICING_CART) : [];
        $chosenId = ArrayHelper::getValue($cart, 'id');
        $chosenQuantity = ArrayHelper::getValue($cart, 'quantity', 1);
        if (!$chosenId) throw new NotFoundHttpException("You have not added any pricing package", 1);
        $pricing = PricingCoin::findOne($chosenId); 
        if (!$pricing || !$pricing->isVisible()) throw new NotFoundHttpException("The package is not found", 1);
        return $this->render('confirm', [
            'model' => $pricing,
            'quantity' => $chosenQuantity
        ]);
    }

    public function actionPurchase()
    {
        $session = Yii::$app->session;
        $cart = $session->has(self::PRICING_CART) ? $session->get(self::PRICING_CART) : [];
        $chosenId = ArrayHelper::getValue($cart, 'id');
        $chosenQuantity = ArrayHelper::getValue($cart, 'quantity', 1);
        if (!$chosenId) throw new NotFoundHttpException("You have not added any pricing package", 1);
        $pricing = PricingCoin::findOne($chosenId); 
        if (!$pricing || !$pricing->isVisible()) throw new NotFoundHttpException("The package is not found", 1);

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

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                // 'AQK-NCCq492D7OEICMTiFzyWPskls32NEhwZ9t7eERBk2kHuhjywMFA8BjMkj1XqFvQTtok6Srs1R-OF',     // ClientID
                // 'EBmAgMX7piQWJu1gkuCbmIRW3MJ1pv-cdYbsxmKj6-esCGhGwCoQ4e-eoQu0d7MCHJxrMKSlY81RFvjx'      // ClientSecret
                $clientId,
                $clientSecret
            )
        );
        $totalPrice = $subTotalPrice = $pricing->amount * $chosenQuantity;
        $ppItem = new Item();
        $ppItem->setName($pricing->title)
        ->setCurrency('USD')
        ->setQuantity($chosenQuantity)
        ->setSku($pricing->id) // Similar to `item_number` in Classic API
        ->setPrice($pricing->amount);
        $itemList[] = $ppItem;
        $ppitemList = new ItemList();
        $ppitemList->setItems($itemList);

        $details = new Details();
        $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal($subTotalPrice);
        // ### Amount
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($totalPrice)
            ->setDetails($details);

        $transaction = new PaypalTransaction();
        $transaction->setAmount($amount)
            ->setItemList($ppitemList)
            ->setDescription("Pay for package of coins #" . $pricing->id . " " . $pricing->title)
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
            	$session = Yii::$app->session;
            	$session->set('payment_method', 'paypal');
            	$session->set('payment_id', $payment->id);
                $session->set('package_id', $pricing->id);
            	$session->set('package_quantity', $chosenQuantity);
                return $this->redirect($payment->getApprovalLink());
            }  
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getData();
        }
    }

    public function actionSuccess()
    {
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $user = Yii::$app->user->getIdentity();

        $paymentId = $request->get('paymentId');
        $payerId = $request->get('PayerID');
        $token = $request->get('token');

        $currentPaymentMethod = $session->get('payment_method');
        $currentPaymentId = $session->get('payment_id');
        $packageId = $session->get('package_id');
        $quantity = $session->get('package_quantity');

        $pricing = PricingCoin::findOne($packageId);
        if (!$pricing) throw new BadRequestHttpException("Some error occured with the package you have chosen.", 1);
        if (!$paymentId || !$payerId || !$token) throw new BadRequestHttpException("The request is invalid", 1);
        if ($paymentId != $currentPaymentId) throw new BadRequestHttpException("The transaction # $paymentId is invalid", 1);

        $settings = Yii::$app->settings;
        $paypalMode = $settings->get('PaypalSettingForm', 'mode', 'sandbox');
        if ($paypalMode == 'live') {
            $clientId = $settings->get('PaypalSettingForm', 'client_id');
            $clientSecret = $settings->get('PaypalSettingForm', 'client_secret');
        } else {
            $clientId = $settings->get('PaypalSettingForm', 'sandbox_client_id');
            $clientSecret = $settings->get('PaypalSettingForm', 'sandbox_client_secret');
        }
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
                $trn->payment_method = $currentPaymentMethod;
                $trn->payment_id = $currentPaymentId;
                $trn->payment_data = $token;
                $trn->amount = $transaction->getAmount()->getTotal();
                $trn->description = "Paypal #$paymentId";
                $trn->created_by = $user->id;
                $trn->status = Transaction::STATUS_COMPLETED;
                $trn->payment_at = date('Y-m-d H:i:s');
                $trn->generateAuthKey();
                $trn->save();

                $wallet = new UserWallet();
                $wallet->coin = $pricing->num_of_coin * $quantity;
                $wallet->type = UserWallet::TYPE_INPUT;
                $wallet->description = "Transaction #$trn->auth_key";
                $wallet->created_by = $user->id;
                $wallet->user_id = $user->id;
                $wallet->status = UserWallet::STATUS_COMPLETED;
                $wallet->payment_at = date('Y-m-d H:i:s');
                $wallet->save();
            }
        } catch (Exception $ex) {
        	$session->remove('payment_method');
	        $session->remove('payment_id');
            $session->remove('package_id');
	        $session->remove('package_quantity');
            exit(1);
        }

        $session->remove('payment_method');
        $session->remove('payment_id');
        $session->remove('package_id');
        $session->remove('package_quantity');
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