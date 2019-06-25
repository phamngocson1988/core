<?php
namespace frontend\components\payment\clients;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\base\Exception;
use zvook\Skrill\Models\QuickCheckout;
use zvook\Skrill\Models\SkrillStatusResponse;
use zvook\Skrill\Components\SkrillException;

// Skrill test merchant email: demoqco@sun-fish.com
// mqi: skrill123, secretword: skrill 
// Skrill test card numbers: VISA: 4000001234567890 | MASTERCARD: 5438311234567890 | AMEX: 371234500012340
class Skrill extends Model
{
    protected $_server = 'https://pay.skrill.com';

    const PAYMENT_STATE_CREATED = 'created';
    const PAYMENT_STATE_APPROVED = 'approved';
    
    public $identifier = 'skrill';

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
        $config['pay_to_email'] = $settings->get('SkrillSettingForm', 'pay_to_email');
        $quickCheckout = new QuickCheckout($config);
        return $quickCheckout;
    }

    protected function loadData($cart)
    {
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
        $checkout->setTransactionId(md5(date('YmdHis') . $user->id));
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

    public function getPaymentLink($cart)
    {
        $checkout = $this->loadData($cart);
        // Get sid from server
        $checkout->setPrepareOnly(1);
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $this->_server); 
        curl_setopt($ch, CURLOPT_POST, TRUE); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $checkout->asArray());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        $sid = curl_exec($ch); 
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch); 
        $this->setReferenceId($checkout->getTransactionId());
        return sprintf("%s?sid=%s", $this->_server, $sid);
    }
    
    public function confirm($response)
    {
        $request = Yii::$app->getRequest();
        try {
            $response = new SkrillStatusResponse($request->post());
        } catch (SkrillException $e) {
            # something bad in request
        }
        $response->log('/kinggerm/common/upload/skrill.log');
        /*
        SkrillStatusResponse model contains attributes only for required Skrill response parameters
        To get all of them use:
        */
        $allParams = $response->getRaw();
        $settings = Yii::$app->settings;
        $secret = $settings->get('SkrillSettingForm', 'secret_word');
        if ($response->verifySignature($secret) && $response->isProcessed()) {
            # bingo! You need to return anything with 200 OK code! Otherwise, Skrill will retry request
            $this->setReferenceId($response->getTransactionId());
            die("OK");
        }

        # Or:

        if ($response->isFailed()) {
            # Note that you should enable receiving failure code in Skrill account before
            # It will not provided with default settings
            $errorCode = $response->getFailedReasonCode();
        }

        /*
        Also you can retrieve any Skrill response parameter and make extra validation you want.
        To see all Skrill response parameters just view SkrillStatusResponse class attributes
        For example:
        */
        if ($response->getPayToEmail() !== 'mymoneybank@mail.com') {
            // hum, it's very strange ...
        }

        /* Also you can log Skrill response data using simple built-in logger */
        $response->log('/path/to/writable/file');
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