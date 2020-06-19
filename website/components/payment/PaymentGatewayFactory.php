<?php
namespace website\components\payment;

use Yii;
use yii\base\Model;

class PaymentGatewayFactory extends Model
{
    public static function getClient($identifier)
    {
        switch ($identifier) {
            case 'kinggems':
                return new \website\components\payment\clients\Kinggems();
                break;
            
            default:
                $paygate = \website\components\payment\clients\OfflinePayment::find()
                ->where(['identifier' => $identifier])
                ->andWhere(['status' => OfflinePayment::STATUS_ACTIVE])
                ->one();
                return $paygate;
        }
        return null;
    }
}