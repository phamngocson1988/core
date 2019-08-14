<?php
namespace frontend\components\payment\clients;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\base\Exception;
use izyue\alipay\AlipayNotify;
use izyue\alipay\AlipaySubmit;

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
        $alipay_config['partner'] = $settings->get('AlipaySettingForm', 'partner');
        $alipay_config['seller_email'] = $settings->get('AlipaySettingForm', 'seller_email');
        $alipay_config['key'] = $settings->get('AlipaySettingForm', 'key');
        $alipay_config['sign_type']    = strtoupper('MD5');
        $alipay_config['input_charset']= strtolower('utf-8');
        $alipay_config['cacert']    = Yii::getAlias('D:\xampp\htdocs\kinggerm\frontend\cacert.pem');//Yii::$app->params['cacert'];//'\cacert.pem';
        $alipay_config['transport']    = 'http';
        return $alipay_config;
    }

    protected function loadData($cart)
    {
        $payment_type = "1";
        $notify_url = $this->getConfirmUrl();
        $return_url = $this->getSuccessUrl();
        $out_trade_no = date('YmdHis');
        $total_fee = $cart->getTotalPrice();
        $show_url = '';//Yii::$app->urlManager->createAbsoluteUrl(['product/view', 'id' => $firstProduct]);
        $anti_phishing_key = time();
        $exter_invoke_ip = "";
        $subject = $body = '';

        $items = array_values($cart->getItems());
        foreach ($items as $key => $cartItem) {
            if ($key == 0) {
                $subject = $cartItem->getTitle();
                if (count($items) > 1) {
                    $subject .= '等' . count($items) . '件商品';
                }
            }
            $body .= $cartItem->getTitle() . ' | ';
        }

        return [
            "payment_type"  => $payment_type,
            "notify_url"    => $notify_url,
            "return_url"    => $return_url,
            "out_trade_no"  => $out_trade_no,
            "subject"   => $subject,
            "total_fee" => $total_fee,
            "body"  => $body,
            "show_url"  => $show_url,
            "anti_phishing_key" => $anti_phishing_key,
            "exter_invoke_ip"   => $exter_invoke_ip,
        ];
    }

    public function getPaymentLink($cart)
    {
        $alipay_config = $this->loadConfig();
        $data = $this->loadData($cart);
        $parameter = array_merge([
            "service" => "create_direct_pay_by_user",
            "partner" => trim($alipay_config['partner']),
            "seller_email" => trim($alipay_config['seller_email']),
            "_input_charset"    => trim(strtolower($alipay_config['input_charset'])),
        ], $data);
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter, "post", "正在跳转支付宝付款，请稍候...");
        echo $html_text;
        die;
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