<?php
namespace website\components\payment;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;

class PaymentGatewayFactory extends Model
{
    public static $clients = [
        'paypal' => [
            'class' => '\website\components\payment\clients\Paypal',
        ],
        'alipay' => [
            'class' => '\website\components\payment\clients\Alipay',
        ],
        'skrill' => [
            'class' => '\website\components\payment\clients\SkrillOffline',
        ],
        'alipay' => [
            'class' => '\website\components\payment\clients\Alipay',
        ],
        'wechat' => [
            'class' => '\website\components\payment\clients\Wechat',
        ],
        'kinggems' => [
            'class' => '\website\components\payment\clients\Kinggems',
        ],
        'postal-savings-bank-of-china' => [
            'class' => '\website\components\payment\clients\PostalSavingsBankOfChina',
        ],
        'payoneer' => [
            'class' => '\website\components\payment\clients\Payoneer',
        ],
        'bitcoin' => [
            'class' => '\website\components\payment\clients\Bitcoin',
        ],
        'western_union' => [
            'class' => '\website\components\payment\clients\WesternUnion',
        ],
        'neteller' => [
            'class' => '\website\components\payment\clients\Neteller',
        ],
        'standard_chartered' => [
            'class' => '\website\components\payment\clients\StandardChartered',
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