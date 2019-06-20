<?php
namespace frontend\components\payment\clients;

use Yii;
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
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

class Paypal extends PaymentClientInterface
{
    public function loadConfig()
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

    public function loadData($cart)
    {
        // $cart = Yii::$app->kingcoin;
        $totalPrice = $cart->getTotalPrice();
        $subTotalPrice = $cart->getSubTotalPrice();
        // $cartItem = $cart->getItem();
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
        $redirectUrls->setReturnUrl(Url::to(['pricing/success'], true))
            ->setCancelUrl(Url::to(['pricing/error'], true));

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));
        return $payment;
    }

    public function request($cart)
    {
        $apiContext = $this->loadConfig();
        $payment = $this->loadData($cart);
        try {
            $payment->create($apiContext);
            if ('created' == strtolower($payment->state)) {// order was created
                return $this->redirect($payment->getApprovalLink());
            }  
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getData();
        }
    }
    public function confirm()
    {
        
    }
}