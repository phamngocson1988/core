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
            'class' => '\frontend\components\payment\clients\SkrillOffline',
        ],
        'alipay' => [
            'class' => '\frontend\components\payment\clients\Alipay',
        ],
        'wechat' => [
            'class' => '\frontend\components\payment\clients\Wechat',
        ],
        'kinggems' => [
            'class' => '\frontend\components\payment\clients\Kinggems',
        ],
        'postal-savings-bank-of-china' => [
            'class' => '\frontend\components\payment\clients\PostalSavingsBankOfChina',
        ],
        'payoneer' => [
            'class' => '\frontend\components\payment\clients\Payoneer',
        ],
        'bitcoin' => [
            'class' => '\frontend\components\payment\clients\Bitcoin',
        ],
        'western_union' => [
            'class' => '\frontend\components\payment\clients\WesternUnion',
        ],
        'neteller' => [
            'class' => '\frontend\components\payment\clients\Neteller',
        ],
        'standard_chartered' => [
            'class' => '\frontend\components\payment\clients\StandardChartered',
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