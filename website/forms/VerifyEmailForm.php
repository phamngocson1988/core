<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use \website\components\verification\email\Email;

class VerifyEmailForm extends Model
{
    const SCENARIO_SEND = 'SCENARIO_SEND';
    const SCENARIO_VERIFY = 'SCENARIO_VERIFY';

    public $email;
    public $code;

    public function rules()
    {
        return [
            [['email', 'code'], 'trim'],
            ['code', 'required', 'on' => self::SCENARIO_SEND],
            ['code', 'required', 'on' => self::SCENARIO_VERIFY]
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_SEND => ['email'],
            self::SCENARIO_VERIFY => ['email', 'code'],
        ];
    }

    protected function getService()
    {
        return new Email();
    }

    public function send()
    {
        $service = $this->getService();
        $email = $this->email;
        if (!$service->send($email, 'Kinggems.us: Your verification code is {pin}')) {
            $this->addError('email', 'Cannot send validation code to this email address.');
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
        $email = $this->email;
        $service = $this->getService();
        if ($service->verify($code)) {
            $user = $this->getUser();
            $user->is_verify_email = 1;
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