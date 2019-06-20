<?php
namespace frontend\components\payment;

use Yii;
use yii\base\Model;
use frontend\components\payment\cart\PaymentCart;

class PaymentGateway extends Model
{
    public $clients = [
        'paypal' => [
            'class' => '\frontend\components\payment\clients\Paypal',
        ]
    ];
    public $return_url = '';
    public $cancel_url = '';

    public function getClient($identifier) 
    {
        $client = ArrayHelper::getValue($this->clients, $identifier);
        if (!$client) return null;
        return Yii::createObject($client);
    }
}