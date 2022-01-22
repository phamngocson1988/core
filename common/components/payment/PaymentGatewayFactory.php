<?php
namespace common\components\payment;

use Yii;
use yii\base\Model;
use common\components\payment\clients\Kinggems;

class PaymentGatewayFactory extends Model
{
    public static function getClient($identifier)
    {
        switch ($identifier) {
            case 'kinggems':
                return new Kinggems();
                break;            
        }
        return null;
    }
}