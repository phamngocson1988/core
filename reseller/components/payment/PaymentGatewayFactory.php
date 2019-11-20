<?php
namespace reseller\components\payment;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;

class PaymentGatewayFactory extends Model
{
    public static $clients = [
        'paypal' => [
            'class' => '\reseller\components\payment\clients\Paypal',
        ],
        'alipay' => [
            'class' => '\reseller\components\payment\clients\Alipay',
        ],
        'skrill' => [
            'class' => '\reseller\components\payment\clients\SkrillOffline',
        ],
        'alipay' => [
            'class' => '\reseller\components\payment\clients\Alipay',
        ],
        'wechat' => [
            'class' => '\reseller\components\payment\clients\Wechat',
        ],
        'kinggems' => [
            'class' => '\reseller\components\payment\clients\Kinggems',
        ],
        'postal-savings-bank-of-china' => [
            'class' => '\reseller\components\payment\clients\PostalSavingsBankOfChina',
        ],
        'payoneer' => [
            'class' => '\reseller\components\payment\clients\Payoneer',
        ],
        'bitcoin' => [
            'class' => '\reseller\components\payment\clients\Bitcoin',
        ],
    ];

    public static function getClient($identifier)
    {
        $clients = self::$clients;
        $clientConfig = ArrayHelper::getValue($clients, $identifier);
        if (!$clientConfig) throw new NotSupportedException("$identifier is not supported");
        return Yii::createObject($clientConfig);
    }
}