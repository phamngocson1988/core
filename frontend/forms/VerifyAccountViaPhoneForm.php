<?php
namespace frontend\forms;

use Yii;
use frontend\models\User;
use yii\helpers\ArrayHelper;

class VerifyAccountViaPhoneForm extends User
{
    public $digit_1;
    public $digit_2;
    public $digit_3;
    public $digit_4;
    public $provider = '\common\components\telecom\SpeedSms';

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
        $phone = sprintf("%s%s", $this->country_code, $this->phone);
        if (!$provider->sms($phone)) {
            $this->addError('phone', $provider->getErrorSummary(true));
            return false;
        }
        return true;
    }

    public function verify()
    {
        if (!$this->validate()) return false;
        $provider = $this->getProvider();
        $phone = sprintf("%s%s", $this->country_code, $this->phone);
        $verification = $this->getVerificationCode();
        if (!$provider->verify($phone, $verification)) {
            $this->addError('verification_code', $provider->getErrorSummary(true));
            return false;
        }
        $this->status = self::STATUS_ACTIVE;
        $this->save();
        return true;
    }

    protected function getVerificationCode()
    {
        return sprintf("%s%s%s%s", $this->digit_1, $this->digit_2, $this->digit_3, $this->digit_4);
    }

    protected function getProvider()
    {
        return Yii::createObject([
            'class' => $this->provider,
        ]);
    }

    public static function findUserByAuth($auth)
    {
        return self::findOne(['auth_key' => $auth, 'status' => self::STATUS_INACTIVE]);
    }
}
