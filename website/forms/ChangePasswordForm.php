<?php
namespace website\forms;

use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $old_password;
    public $new_password;
    public $re_password;

    private $_user;

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_password', 'new_password', 're_password'], 'required'],
            ['old_password', 'validatePassword'],
            ['re_password', 'compare', 'compareAttribute' => 'new_password'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'old_password' => 'Old Password',
            'new_password' => 'New Password',
            're_password' => 'Re-type New Password',
        ];
    }

    public function change()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->setPassword($this->new_password);
            $user->removePasswordResetToken();
            if ($user->save(false)) {
                $toEmail = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
                $siteName = Yii::$app->name;
                Yii::$app->mailer->compose('change_password', ['user' => $user])
                ->setTo($user->email)
                ->setFrom([$toEmail => $siteName])
                ->setSubject('[Kinggems.us]- Changed password Successfully')
                ->setTextBody("[Kinggems.us]- Changed password Successfully")
                ->send();
                return true;
            }
        }
        return false;
    }


    protected function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->old_password)) {
                $this->addError($attribute, Yii::t('app', 'incorrect_password'));
            }
        }
    }
}

