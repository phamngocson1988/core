<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * CreateCustomerForm
 */
class CreateCustomerForm extends Model
{
    public $name;
    public $username;
    public $email;
    public $country_code;
    public $phone;
    public $address;
    public $birthday;
    public $password;
    public $status;
    public $saler_id;
    public $send_mail = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['username', 'name'], 'trim'],

            [['country_code', 'phone'], 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'min' => 7],

            [['saler_id', 'address', 'birthday', 'status', 'send_mail'], 'safe']
        ];
    }

    /**
     * Signs user up.
     *
     * @return Customer|null the saved model or null if saving fails
     */
    public function create()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->name = $this->name;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->country_code = $this->country_code;
        $user->phone = $this->phone;
        $user->address = $this->address;
        $user->birthday = $this->birthday;
        $user->saler_id = $this->saler_id;
        $user->status = $this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->save() ? $user : null;
        if ($this->send_mail) {
            $this->status ? $this->sendEmailNotification($user) : $this->sendMailActivation($user);
        }
        return true;
    }

    public function sendMailActivation($user)
    {
        $settings = Yii::$app->settings;
        $mailer = Yii::$app->mailer;
        $subject = sprintf('King Gems - New account is created');
        $template = 'active_customer';
        $fromEmail = $settings->get('ApplicationSettingForm', 'customer_service_email');
        $toEmail = $user->email;
        $message = $mailer->compose($template, [
            'activeUrl' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['site/activate', 'id' => $user->id, 'key' => $user->auth_key], true),
            'user' => $user,
            'password' => $this->password
        ]);
        $message->setFrom($fromEmail);
        $message->setTo($toEmail);
        $message->setSubject($subject);
        $message->send($mailer);
    }

    public function sendEmailNotification($user)
    {
        $settings = Yii::$app->settings;
        $mailer = Yii::$app->mailer;
        $subject = sprintf('King Gems - New account is created');
        $template = 'invite_customer';
        $fromEmail = $settings->get('ApplicationSettingForm', 'customer_service_email');
        $toEmail = $user->email;
        $message = $mailer->compose($template, [
            'user' => $user,
            'password' => $this->password
        ]);
        $message->setFrom($fromEmail);
        $message->setTo($toEmail);
        $message->setSubject($subject);
        $message->send($mailer);
    }
}
