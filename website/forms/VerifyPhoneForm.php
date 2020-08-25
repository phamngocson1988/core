<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use \website\components\verification\twilio\Sms;

class VerifyPhoneForm extends Model
{
    const SCENARIO_SEND = 'SCENARIO_SEND';
    const SCENARIO_VERIFY = 'SCENARIO_VERIFY';

    public $phone;
    public $code;

    public function rules()
    {
        return [
            [['phone', 'code'], 'trim'],
            ['phone', 'required'],
            ['code', 'required', 'on' => self::SCENARIO_VERIFY]
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_SEND => ['phone'],
            self::SCENARIO_VERIFY => ['phone', 'code'],
        ];
    }

    protected function getService()
    {
        return new Sms();
    }

    public function send()
    {
        $service = $this->getService();
        $phone = $this->phone;
        if (!$service->send($phone, 'Kinggems.us: Your verification code is {pin}')) {
            $this->addError('phone', 'Cannot send validation code to this phone number.');
            return false;
        }
        $user = $this->getUser();
        if ($user) {
            $user->security_pin = Yii::$app->session->get('pin');
            $user->save();
        }
        return true;
    }

    public function verify()
    {
        $code = $this->code;
        $phone = $this->phone;
        $service = $this->getService();
        if ($service->verify($code)) {
            $user = $this->getUser();
            $user->phone = $this->phone;
            return $user->save();
        } 
        $this->addError('code', 'The code is invalid');
        return false;
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }
}