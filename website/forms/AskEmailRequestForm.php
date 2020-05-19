<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\User;

class AskEmailRequestForm extends Model
{
    public $phone;
    protected $_user;

    public function rules()
    {
        return [
            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'validatePhone']
        ];
    }

    public function validatePhone($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, 'This phone number is not exist in our system.');
            return;
        }
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'phone' => $this->phone,
            ]);
        }
        return $this->_user;
    }

    public function send()
    {
        $user = $this->getUser();
        return Yii::$app->sms->compose()
            ->setTo($this->phone) //+8618579804779
            ->setMessage(sprintf("Kinggems.us: Your email account is %s", $user->email))
            ->send();
    }
}

