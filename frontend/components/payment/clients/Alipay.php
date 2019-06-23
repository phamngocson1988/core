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

class Alipay extends Model
{
    const PAYMENT_STATE_CREATED = 'created';
    const PAYMENT_STATE_APPROVED = 'approved';
    
    public $identifier = 'alipay';

    protected $params = [
        'paymentId',
        'PayerID',
        'token',
    ];
    protected $confirm_url;
    protected $success_url;
    protected $cancel_url;
    protected $error_url;

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

    public function setConfirmUrl($url)
    {
        $this->confirm_url = $url;
    }

    public function getConfirmUrl()
    {
        return $this->confirm_url;
    }

    public function setSuccessUrl($url)
    {
        $this->success_url = $url;
    }

    public function getSuccessUrl()
    {
        return $this->success_url;
    }

    public function setCancelUrl($url)
    {
        $this->cancel_url = $url;
    }

    public function getCancelUrl()
    {
        return $this->cancel_url;
    }

    public function setErrorUrl($url)
    {
        $this->error_url = $url;
    }

    public function getErrorUrl()
    {
        return $this->error_url;
    }

    protected function loadConfig()
    {
        $settings = Yii::$app->settings;
        $config['partner'] = $settings->get('AlipaySettingForm', 'partner');
        $config['seller_email'] = $settings->get('AlipaySettingForm', 'seller_email');
        $config['key'] = $settings->get('AlipaySettingForm', 'key');
        $config['sign_type'] = strtoupper('MD5');
        $config['input_charset'] = strtolower('utf-8');
        $config['cacert'] = __DIR__ . '/cacert.pem';
        $config['transport'] = 'http';
        return $config;
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

        $email = $_POST['WIDemail'];
        $account_name = "北京纽斯洛网络科技有限公司";
        $pay_date = date("Y-m-d");
        $batch_no = date("YmdHis");
        $batch_fee = round($totalPrice, 2);
        $batch_num = 2;
        //必填，即参数detail_data的值中，“|”字符出现的数量加1，最大支持1000笔（即“|”字符出现的数量999个）

        //付款详细数据
        $detail_data = "流水号1^收款方帐号1^真实姓名^0.01^测试付款1,这是备注|流水号2^收款方帐号2^真实姓名^0.01^测试付款2,这是备注";
        //必填，格式：流水号1^收款方帐号1^真实姓名^付款金额1^备注说明1|流水号2^收款方帐号2^真实姓名^付款金额2^备注说明2....

    }

    public function getPaymentLink($cart)
    {
        

        /************************************************************/

        $alipayConfig = $this->loadConfig();
        $parameter = array(
            "service" => "batch_trans_notify",
            "partner" => trim($alipayConfig['partner']),
            "notify_url"    => $this->getConfirmUrl(),
            "email" => trim($alipayConfig['seller_email']),

            "account_name"  => $account_name,
            "pay_date"  => $pay_date,
            "batch_no"  => $batch_no,
            "batch_fee" => $batch_fee,
            "batch_num" => $batch_num,
            "detail_data"   => $detail_data,
            "_input_charset"    => trim(strtolower($alipayConfig['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipayConfig);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        return $html_text;
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

    public function success()
    {
        return Yii::$app->getResponse()->redirect($this->getSuccessUrl(), 302);
    }

    public function error()
    {
        return Yii::$app->getResponse()->redirect($this->getErrorUrl(), 302);
    }

    public function cancel($response)
    {
        extract($response); // now can use $token
        $this->setReferenceId($token);
        return true;
    }
}