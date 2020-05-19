<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\User;

class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 
                'targetClass' => User::className(), 
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    public function sendEmail()
    {

        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $this->email,

        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'customer_service_email', null);
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([$adminEmail => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Reset password Kinggems')
            ->send();
    }
}

