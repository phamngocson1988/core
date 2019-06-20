<?php
namespace frontend\components\payment;

use Yii;
use yii\base\Model;
use frontend\components\payment\cart\PaymentCart;
use yii\helpers\ArrayHelper;

class PaymentGateway extends Model
{
    const EVENT_BEFORE_REQUEST = 'EVENT_BEFORE_REQUEST';
    const EVENT_BEFORE_CONFIRM = 'EVENT_BEFORE_CONFIRM';
    const EVENT_AFTER_CONFIRM = 'EVENT_AFTER_CONFIRM';
    
    public $clients = [
        'paypal' => [
            'class' => '\frontend\components\payment\clients\Paypal',
        ]
    ];
    public $return_url = '';
    public $cancel_url = '';

    protected $client;
    protected $cart;

    public function getClient($identifier) 
    {
        $client = ArrayHelper::getValue($this->clients, $identifier);
        if (!$client) return null;
        return Yii::createObject($client);
    }

    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    public function setClient($client);
    {
        $this->client = $client;
    }

    public function request()
    {
        $this->trigger(self::EVENT_BEFORE_REQUEST);
        return $this->client->request();
    }

    public function confirm()
    {
        $this->trigger(self::EVENT_BEFORE_CONFIRM);
        $result = $this->client->confirm();
        if ($result) {
            $this->trigger(self::EVENT_AFTER_CONFIRM);
        }
        return $result;
    }
}