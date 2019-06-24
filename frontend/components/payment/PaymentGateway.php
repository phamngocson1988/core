<?php
namespace frontend\components\payment;

use Yii;
use yii\base\Model;
use frontend\components\payment\cart\PaymentCart;
use yii\helpers\ArrayHelper;
use frontend\components\payment\events\BeforeRequestEvent;
use yii\helpers\Url;

class PaymentGateway extends Model
{
    const EVENT_BEFORE_REQUEST = 'EVENT_BEFORE_REQUEST';
    const EVENT_BEFORE_CONFIRM = 'EVENT_BEFORE_CONFIRM';
    const EVENT_AFTER_CONFIRM = 'EVENT_AFTER_CONFIRM';
    const EVENT_BEFORE_CANCEL = 'EVENT_BEFORE_CANCEL';
    const EVENT_AFTER_CANCEL = 'EVENT_AFTER_CANCEL';
    const EVENT_CONFIRM_SUCCESS = 'EVENT_CONFIRM_SUCCESS';
    const EVENT_CONFIRM_ERROR = 'EVENT_CONFIRM_ERROR';

    public $clients = [
        'paypal' => [
            'class' => '\frontend\components\payment\clients\Paypal',
        ],
        'alipay' => [
            'class' => '\frontend\components\payment\clients\Alipay',
        ]
    ];
    public $confirm_url = 'pricing/verify';
    public $success_url = 'pricing/success';
    public $cancel_url = 'pricing/cancel';
    public $error_url = 'pricing/error';

    protected $client;
    protected $cart;

    public function __construct($identifier) 
    {
        $clientData = ArrayHelper::getValue($this->clients, $identifier);
        if (!$clientData) return null;
        $client = Yii::createObject($clientData);
        $client->setConfirmUrl(Url::to([$this->confirm_url, 'identifier' => $client->identifier], true));
        $client->setSuccessUrl(Url::to([$this->success_url], true));
        $client->setCancelUrl(Url::to([$this->cancel_url, 'identifier' => $client->identifier], true));
        $client->setErrorUrl(Url::to([$this->error_url], true));
        $this->setClient($client);
    }

    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function request()
    {
        $client = $this->getClient();
        $link = $client->getPaymentLink($this->cart);
        $this->trigger(self::EVENT_BEFORE_REQUEST);
        return Yii::$app->getResponse()->redirect($link, 302);
    }

    public function confirm()
    {
        $client = $this->getClient();
        $params = $client->getResponseParams();
        $response = [];
        foreach ($params as $name) {
            $response[$name] = $this->getQueryParam($name);
        }
        $this->trigger(self::EVENT_BEFORE_CONFIRM);
        $result = $client->confirm($response);
        $this->trigger(self::EVENT_AFTER_CONFIRM);
        if ($result) {
            $this->trigger(self::EVENT_CONFIRM_SUCCESS);
            $client->success();
        } else {
            $this->trigger(self::EVENT_CONFIRM_ERROR);
            $client->error();
        }
        return $result;
    }

    public function cancel()
    {
        $client = $this->getClient();
        $params = $client->getResponseParams();
        $response = [];
        foreach ($params as $name) {
            $response[$name] = $this->getQueryParam($name);
        }
        $this->trigger(self::EVENT_BEFORE_CANCEL);
        $result = $client->cancel($response);
        if ($result) {
            $this->trigger(self::EVENT_AFTER_CANCEL);
        }
        return $result;
    }

    protected function getQueryParam($name, $defaultValue = null)
    {
        $request = Yii::$app->getRequest();
        $params = $request->getQueryParams();
        return isset($params[$name]) && is_scalar($params[$name]) ? $params[$name] : $defaultValue;
    }
}