<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\User;

class AskEmailRequestForm extends Model
{
    public $phone;
    public $country_code;
    protected $_user;

    public function rules()
    {
        return [
            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'validatePhone'],

            ['country_code', 'trim'],
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

    public function askEmail()
    {
        if (!$this->validate()) return false;
        $user = $this->getUser();
        $email = $user->email;

        // Hide email
        $em   = explode("@", $email);
        $name = implode('@', array_slice($em, 0, count($em) - 1));
        $len = strlen($name);
        $numHide = ceil($len / 3);
        return substr($name, 0, $numHide - 1) . str_repeat('*', $numHide) . substr($name, (2 * $numHide) - 1) ."@" . end($em);   
    }
}

