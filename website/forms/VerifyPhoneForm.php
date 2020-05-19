<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use \website\components\verification\twilio\Sms;

class VerifyPhoneForm extends Model
{
    public $phone;
    public $code;

    public function rules()
    {
        return [
            [['phone', 'code'], 'trim'],
            [['phone', 'code'], 'required'],
        ];
    }

    protected function getService()
    {
        return new Sms();
    }
    public function send($phone)
    {
        $service = $this->getService();
        return $service->send($phone, 'Kinggems.us: Your verification code is {pin}');
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