<?php
namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\User;

class PaymentTransaction extends \common\models\PaymentTransaction
{
    const SCENARIO_CONFIRM_OFFLINE_PAYMENT = 'SCENARIO_CONFIRM_OFFLINE_PAYMENT';

    public function scenarios()
    {
        return [
            self::SCENARIO_CONFIRM_OFFLINE_PAYMENT => ['payment_id', 'status']
        ];
    }

    public function rules()
    {
        return [
            [['payment_id', 'status'], 'required']
        ];
    }
}