<?php
namespace api\components\payment;

use Yii;
use yii\base\Model;
use api\components\payment\clients\Kinggems;

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