<?php
namespace frontend\forms;

use Yii;
use frontend\models\User;
use yii\helpers\ArrayHelper;
use frontend\events\AfterActiveEvent;

class VerifyAccountViaPhoneForm extends User
{
    const EVENT_AFTER_ACTIVE = 'EVENT_AFTER_ACTIVE';

    public $digit_1;
    public $digit_2;
    public $digit_3;
    public $digit_4;
    public $provider = [
        'class' => '\common\components\telecom\SpeedSms',
        'demo_mode' => true
    ];

    public function rules()
    {
        return [
            [['digit_1', 'digit_2', 'digit_3', 'digit_4'], 'required'],
            [['digit_1', 'digit_2', 'digit_3', 'digit_4'], 'number', 'max' => 9],
        ];
    }

    public function send()
    {
        $provider = $this->getProvider();
        $phone = $this->phone;
        if (!$provider->sms($phone)) {
            $errors = $provider->getErrorSummary(false);
            $this->addError('phone', reset($errors));
            return false;
        }
        return true;
    }

    public function verify()
    {
        if (!$this->validate()) return false;
        $provider = $this->getProvider();
        $phone = $this->phone;
        $verification = $this->getVerificationCode();
        if (!$provider->verify($phone, $verification)) {
            $errors = $provider->getErrorSummary(false);
            $this->addError('verification_code', reset($errors));
            return false;
        }
        $this->status = self::STATUS_ACTIVE;
        $this->save();

        $this->trigger(self::EVENT_AFTER_ACTIVE);
        return true;
    }

    protected function getVerificationCode()
    {
        return sprintf("%s%s%s%s", $this->digit_1, $this->digit_2, $this->digit_3, $this->digit_4);
    }

    protected function getProvider()
    {
        return Yii::createObject($this->provider);
    }

    public static function findUserByAuth($auth)
    {
        return self::findOne(['auth_key' => $auth, 'status' => self::STATUS_INACTIVE]);
    }
}
