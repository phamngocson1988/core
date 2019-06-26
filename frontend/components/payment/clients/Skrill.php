<?php
namespace frontend\components\payment\clients;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\base\Exception;
use frontend\components\payment\PaymentGateway;

use zvook\Skrill\Models\QuickCheckout;
use zvook\Skrill\Models\SkrillStatusResponse;
use zvook\Skrill\Components\SkrillException;

// Skrill test merchant email: demoqco@sun-fish.com
// mqi: skrill123, secretword: skrill 
// Skrill test card numbers: VISA: 4000001234567890 | MASTERCARD: 5438311234567890 | AMEX: 371234500012340
class Skrill extends PaymentGateway
{
    protected $_server = 'https://pay.skrill.com';
    public $identifier = 'skrill';

    protected function loadConfig()
    {
        $settings = Yii::$app->settings;
        $config['pay_to_email'] = $settings->get('SkrillSettingForm', 'pay_to_email');
        $quickCheckout = new QuickCheckout($config);
        return $quickCheckout;
    }

    protected function loadData()
    {
        $cart = $this->cart;
        $user = Yii::$app->user->getIdentity();
        $checkout = $this->loadConfig();
        $checkout->setAmount($cart->getTotalPrice());
        $checkout->setCurrency('USD');

        $subTotal = $cart->getSubTotalPrice();
        $checkout->setAmount2($subTotal);
        $items = $cart->getItems();
        $itemTitles = array_column($items, 'title');
        $checkout->setAmount2Description(implode("|", $itemTitles));

        $discount = $cart->hasDiscount();
        if ($discount) {
            $checkout->setAmount3((-1) * $discount);
            $checkout->setAmount3Description('Discount');
        }

        $checkout->setRecipientDescription('Kinggems');
        $checkout->setTransactionId($this->getReferenceId());
        $checkout->setLogoUrl('https://kinggems.us/images/logo-default-128x52.png');
        $checkout->setPayFromEmail($user->email);

        $checkout->setReturnUrl($this->getSuccessUrl());
        $checkout->setReturnUrlTarget(QuickCheckout::URL_TARGET_SELF);
        $checkout->setReturnUrlText('Get back to Kinggems');

        $checkout->setCancelUrl($this->getCancelUrl());
        $checkout->setCancelUrlTarget(QuickCheckout::URL_TARGET_SELF);
        
        $checkout->setStatusUrl($this->getConfirmUrl());
        return $checkout;
    }

    protected function sendRequest()
    {
        $checkout = $this->loadData();
        // Get sid from server
        $checkout->setPrepareOnly(1);
        $server = $this->getServer();
        $params = $checkout->asArray();
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $server); 
        curl_setopt($ch, CURLOPT_POST, TRUE); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        $sid = curl_exec($ch); 
        $sidError = json_decode($sid, true);
        // if json decode does not throw error code, it means the sid is an object of error message
        // Example {"code":"BAD_REQUEST","message":"Missing%20pay_to_email%20or%20merchant_id%20parameter"}
        if (!json_last_error()) throw new SkrillException($sidError['message']);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 

        function isJson($string) {
            return ((is_string($string) &&
                    (is_object(json_decode($string)) ||
                    is_array(json_decode($string))))) ? true : false;
        }

        curl_close($ch); 
        // $this->setReferenceId($checkout->getTransactionId());
        $link = sprintf("%s?sid=%s", $server, $sid);
        return $this->redirect($link);
    }
    
    protected function verify($responseParams)
    {
        try {
            $response = new SkrillStatusResponse($responseParams);
            $allParams = $response->getRaw();
            $settings = Yii::$app->settings;
            $secret = $settings->get('SkrillSettingForm', 'secret_word');
            return $response->verifySignature($secret) && $response->isProcessed();
        } catch (SkrillException $e) {
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

    public function cancelPayment()
    {
        return true;
    }

    protected function getServer()
    {
        return $this->_server;
    }

    protected function doSuccess()
    {
        Yii::$app->response->statusCode = 200;
        return;
    }

    protected function doError()
    {
        throw new SkrillException('Error');
    }
}