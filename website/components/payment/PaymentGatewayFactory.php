<?php
namespace website\components\payment;

use Yii;
use yii\base\Model;
use website\components\payment\clients\OfflinePayment;
use website\components\payment\clients\Kinggems;
use website\models\Paygate;

class PaymentGatewayFactory extends Model
{
    public static function getConfig($identifier)
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

    public static function getPaygate($paygateModal)
    {
        switch ($paygateModal->getPaymentType()) {
            case Paygate::PAYGATE_TYPE_OFFLINE:
                return new \website\components\payment\paygate\OfflinePaygate($paygateModal);
            
            case Paygate::PAYGATE_TYPE_ONLINE:
                switch ($paygateModal->getIdentifier()) {
                    case 'kinggems':
                        return new \website\components\payment\paygate\Kinggems($paygateModal);
                    case 'coinbase':
                        return new \website\components\payment\paygate\CoinBase($paygateModal);
                    case 'coinspaid':
                        return new \website\components\payment\paygate\CoinsPaid($paygateModal);
                    case 'webmoney':
                        return new \website\components\payment\paygate\WebMoney($paygateModal);
                }
        }
        return null;
    }
}