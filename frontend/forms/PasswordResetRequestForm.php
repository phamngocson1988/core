<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;

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
                'message' => Yii::t('app', 'no_message_with_email'),
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
        $adminEmail = Yii::$app->params['admin_email'];
        return Yii::$app
            ->mailer
            ->compose('passwordResetToken-html',
                ['user' => $user]
            )
            ->setFrom([$adminEmail => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject(sprintf('[%s] Reset password', Yii::$app->name))
            ->send();
    }
}

