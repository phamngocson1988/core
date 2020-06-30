<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\User;
use yii\helpers\Url;

class ReferralInvitationForm extends Model
{
    public $user_id;
    public $email;

    protected $_user;

    public function init()
    {

    }
    public function rules()
    {
        return [
            ['user_id', 'required'],
            ['email', 'validateEmail'],
        ];
    }

    public function validateEmail($attribute, $params)
    {
        $this->email = array_filter((array)$this->email, function($item) {
            return filter_var($item, FILTER_VALIDATE_EMAIL);
        });
        if (!count($this->email)) {
            return $this->addError($attribute, 'You need to add at least one valid email');
        }
    }

    public function validateUser($attribute, $params)
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, 'We cannot detact who you are');
        }
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }

    public function send()
    {
        $user = $this->getUser();
        $code = $user->refer_code;
        $link = Url::to(['site/register', 'refer' => $code], true);
        $setting = Yii::$app->settings;
        $mailer = Yii::$app->mailer;
        $admin =  $setting->get('ApplicationSettingForm', 'admin_email', null);
        foreach ($this->email as $email) {
            $mailer->compose('refer_mail', ['user' => $user, 'link' => $link])
            ->setTo($email)
            ->setFrom([$admin => Yii::$app->name])
            ->setSubject(sprintf("[%s][Refer Email] You have received refer link from %s ", Yii::$app->name, $user->name))
            ->setTextBody(sprintf("%s have just sent you a link: %s", $user->name, $link))
            ->send();
        }
        return true;
    }
}
