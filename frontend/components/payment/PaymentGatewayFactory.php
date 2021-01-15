<?php
namespace frontend\components\payment;

use Yii;
use yii\base\Model;
use frontend\components\payment\clients\OfflinePayment;
use frontend\components\payment\clients\Kinggems;

class PaymentGatewayFactory extends Model
{
    public static function getClient($identifier)
    {
        switch ($identifier) {
            case 'kinggems':
                return new Kinggems();
                break;
            
            default:
                $paygate = OfflinePayment::find()
                ->where(['identifier' => $identifier])
                ->andWhere(['status' => OfflinePayment::STATUS_ACTIVE])
                ->one();
                return $paygate;
        }
        return null;
    }
}