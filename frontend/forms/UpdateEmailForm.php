<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;

class UpdateEmailForm extends Model
{
    public $new_email;
    public $confirm_email;
    public $password;

    public function rules()
    {
        return [
            [['new_email', 'confirm_email', 'password'], 'required'],

            ['new_email', 'trim'],
            ['new_email', 'string', 'max' => 255],

            ['confirm_email', 'trim'],
            ['confirm_email', 'string', 'max' => 255],

            ['confirm_email', 'compare', 'compareAttribute' => 'new_email', 'message' => Yii::t('app', "Email does not match") ],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect password'));
            }
        }
    }

    public function save()
    {
        $user = $this->getUser();
        $user->email = $this->new_email;
        return $user->save();
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }
}
