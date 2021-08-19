<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\User;

class PasswordResetRequestForm extends Model
{
    public $email;

    protected $_user;

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
            ['email', 'isStaff', 'message' => 'Email này không tồn tại trong hệ thống'],
        ];
    }

    protected function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne([
                'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
            ]);
        }
        return $this->_user;
    }
    public function isStaff($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $auth = Yii::$app->authManager;
            $user = $this->getUser();
            if (!$user->isSupplier()) {
                $this->addError($attribute, 'Email này không tồn tại trong hệ thống');
                return false;
            } else {
                $supplier = $user->supplier;
                if (!$supplier->isEnabled()) {
                    $this->addError($attribute, 'Tài khoản này chưa được kích hoạt');
                    return false;
                }
            }
        }
    }

    public function sendEmail()
    {
        $user = $this->getUser();
        if (!$user) {
            return false;
        }
        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        $emailHelper = new \common\components\helpers\MailHelper();
        return $emailHelper
        ->setMailer(Yii::$app->supplier_mailer)
        ->usingSupplierService()
        ->usingSupplierSiteName()
        ->send(
            'Reset password HoangGiaNapGame',
            $user->email,
            'passwordResetToken-html',
            ['user' => $user]
        );
    }
}

