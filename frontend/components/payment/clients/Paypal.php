<?php
namespace frontend\components\payment\clients;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\base\Exception;

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

class Paypal extends Model
{
    const PAYMENT_STATE_CREATED = 'created';
    const PAYMENT_STATE_APPROVED = 'approved';
    
    public $identifier = 'paypal';

    protected $params = [
        'paymentId',
        'PayerID',
        'token',
    ];
    protected $return_url;
    protected $cancel_url;

    protected $reference_id;

    protected function setReferenceId($reference_id)
    {
        $this->reference_id = $reference_id;
    }

    public function getReferenceId()
    {
        return $this->reference_id;
    }

    public function getResponseParams()
    {
        return $this->params;
    }

    public function setReturnUrl($url)
    {
        $this->return_url = $url;
    }

    public function getReturnUrl()
    {
        return $this->return_url;
    }

    public function setCancelUrl($url)
    {
        $this->cancel_url = $url;
    }

    public function getCancelUrl()
    {
        return $this->cancel_url;
    }

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

    protected function loadData($cart)
    {
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
            ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($this->getReturnUrl())
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

    public function getPaymentLink($cart)
    {
        $apiContext = $this->loadConfig();
        $payment = $this->loadData($cart);
        try {
            $payment->create($apiContext);
            if (self::PAYMENT_STATE_CREATED == strtolower($payment->state)) {// order was created
                $link = $payment->getApprovalLink();
                $query = parse_url($link, PHP_URL_QUERY);
                parse_str($query, $params);
                $token = isset($params['token']) ? $params['token'] : '';
                $this->setReferenceId($token);
                return $link;
            }  
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getData();
        }
    }
    
    public function confirm($response)
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
            $this->setReferenceId($token);
            return self::PAYMENT_STATE_APPROVED == strtolower($payment->state);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function cancel($response)
    {
        extract($response); // now can use $token
        $this->setReferenceId($token);
        return true;
    }
}