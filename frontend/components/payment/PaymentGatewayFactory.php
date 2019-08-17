<?php
namespace frontend\components\payment;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;

class PaymentGatewayFactory extends Model
{
    public static $clients = [
        'paypal' => [
            'class' => '\frontend\components\payment\clients\Paypal',
        ],
        'alipay' => [
            'class' => '\frontend\components\payment\clients\Alipay',
        ],
        'skrill' => [
            'class' => '\frontend\components\payment\clients\Skrill',
        ],
        'alipay' => [
            'class' => '\frontend\components\payment\clients\Alipay',
        ],
        'wechat' => [
            'class' => '\frontend\components\payment\clients\Wechat',
        ],
        'kinggems' => [
            'class' => '\frontend\components\payment\clients\Kinggems',
        ]
    ];

    public static function getClient($identifier)
    {
        $clients = self::$clients;
        $clientConfig = ArrayHelper::getValue($clients, $identifier);
        if (!$clientConfig) throw new NotSupportedException("$identifier is not supported");
        return Yii::createObject($clientConfig);
    }
}