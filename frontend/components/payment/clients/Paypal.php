<?php
namespace frontend\components\payment\clients;

use Yii;
use yii\web\BadRequestHttpException;
use yii\base\Exception;
use frontend\components\payment\PaymentGateway;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction as PaypalTransaction;
use PayPal\Api\PaymentExecution;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;

class Paypal extends PaymentGateway
{
    const PAYMENT_STATE_CREATED = 'created';
    const PAYMENT_STATE_APPROVED = 'approved';
    
    public $identifier = 'paypal';

    protected $confirmParams = [
        'paymentId',
        'PayerID',
        'token',
    ];

    protected function loadConfig()
    {
        $settings = Yii::$app->settings;
        $paypalMode = $settings->get('PaypalSettingForm', 'mode', 'sandbox');
        if ($paypalMode == 'live') {
            $clientId = $settings->get('PaypalSettingForm', 'client_id');
            $clientSecret = $settings->get('PaypalSettingForm', 'client_secret');
        } else {
            $clientId = $settings->get('PaypalSettingForm', 'sandbox_client_id');
            $clientSecret = $settings->get('PaypalSettingForm', 'sandbox_client_secret');
        }
        return new ApiContext(new OAuthTokenCredential($clientId, $clientSecret));
    }

    protected function loadData()
    {
        $cart = $this->cart;
        $totalPrice = $cart->getTotalPrice();
        $currency = "USD";

        $itemList = [];
        foreach ($cart->getItems() as $cartItem) {
            $ppItem = new Item();
            $ppItem->setName($cartItem->getTitle())
            ->setCurrency($currency)
            ->setQuantity($cartItem->getQuantity())
            ->setSku($cartItem->getId())
            ->setPrice($cartItem->getPrice());
            $itemList[] = $ppItem;
        }

        // For discount
        if ($cart->hasDiscount()) {
            $discount = $cart->getDiscount();
            $discountItem = new Item();
            $discountItem->setName($discount->getTitle())
            ->setCurrency($currency)
            ->setQuantity(1)
            ->setSku($discount->getId())
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
        $amount->setCurrency($currency)
            ->setTotal($totalPrice)
            ->setDetails($details);

        $transaction = new PaypalTransaction();
        $transaction->setAmount($amount)
            ->setItemList($ppitemList)
            ->setDescription($cart->getTitle())
            ->setInvoiceNumber($this->getReferenceId());
            // ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->getConfirmUrl())
            ->setCancelUrl($this->getCancelUrl());

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        return $payment;
    }

    protected function sendRequest()
    {
        $apiContext = $this->loadConfig();
        $payment = $this->loadData();
        try {
            $payment->create($apiContext);
            if (self::PAYMENT_STATE_CREATED == strtolower($payment->state)) {// order was created
                $link = $payment->getApprovalLink();
                $query = parse_url($link, PHP_URL_QUERY);
                parse_str($query, $params);
                return $this->redirect($link);
            }  
        } catch (PayPalConnectionException $ex) {
            throw $ex;
        }
    }
    
    protected function verify($response)
    {
        extract($response); // now can use $paymentId, $PayerID, $token
        if (!$paymentId || !$PayerID || !$token) throw new BadRequestHttpException("The request is invalid", 1);

        $apiContext = $this->loadConfig();
        $payment = Payment::get($paymentId, $apiContext);
        if (self::PAYMENT_STATE_CREATED != strtolower($payment->state)) throw new BadRequestHttpException("Transaction #$paymentId : status is invalid", 1);
        $execution = new PaymentExecution();
        $execution->setPayerId($PayerID);
        $transactions = $payment->getTransactions();
        $transaction = reset($transactions);
        $execution->addTransaction($transaction);
        try {
            $payment->execute($execution, $apiContext);
            return self::PAYMENT_STATE_APPROVED == strtolower($payment->state);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function cancelPayment()
    {
        return true;
    }

    public function doSuccess()
    {
        $refId = $this->getReferenceId();
        return $this->redirect($this->getSuccessUrl(['ref' => $refId]));
    }

    public function doError()
    {
        return $this->redirect($this->getErrorUrl());
    }
}