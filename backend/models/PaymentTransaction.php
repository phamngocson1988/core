<?php
namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\User;
use backend\models\Order;

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
            ['payment_id', 'trim'],
            [['payment_id', 'status'], 'required'],
            ['payment_id', 'validatePaymentId', 'on' => self::SCENARIO_CONFIRM_OFFLINE_PAYMENT]
        ];
    }

    public function validatePaymentId($attribute, $params = [])
    {
        if ($this->hasErrors()) return false;
        $order = Order::find()->where(['payment_id' => $this->payment_id])->one();
        if ($order) {
            return $this->addError($attribute, sprintf('This payment id was used for order (%s)', $order->id));
        }
        $payment = self::find()->where(['payment_id' => $this->payment_id])->one();
        if (!$payment) return true;
        if ($payment->id != $this->id) {
            $this->addError($attribute, sprintf('Duplicated payment id with transaction num %s', $payment->getId()));
            return false;
        }
    }
}