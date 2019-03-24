<?php
namespace frontend\forms;

use Yii;
use common\models\User;

class ChangePasswordForm extends User
{
    public $old_password;
    public $new_password;
    public $repeat_password;

    public function rules()
    {
        return [
            [['old_password', 'new_password', 'repeat_password'], 'required'],
            ['old_password', 'checkPassword'],
            ['repeat_password', 'compare', 'compareAttribute'=>'new_password']
        ];
    }

    public function checkPassword($attribute, $params)
    {
        if (!$this->validatePassword($this->old_password)) {
            $this->addError($attribute, 'Old password is incorrect.');
        }
    }

    public function change()
    {
        $this->setPassword($this->new_password);
        $this->removePasswordResetToken();
        return $this->save();
    }
}

