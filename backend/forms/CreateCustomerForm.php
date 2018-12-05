<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Customer;

/**
 * CreateCustomerForm
 */
class CreateCustomerForm extends Model
{
    public $name;
    public $username;
    public $email;
    public $phone;
    public $address;
    public $birthday;
    public $social_line;
    public $social_zalo;
    public $social_facebook;
    public $password;
    public $status;
    public $send_mail = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => Yii::t('app', 'validate_alphanumeric')],
            ['username', 'unique', 'targetClass' => '\backend\models\Customer', 'message' => Yii::t('app', 'validate_username_unique')],


            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\Customer', 'message' => Yii::t('app', 'validate_email_unique')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['status', 'in', 'range' => array_keys(Customer::getUserStatus())],

            [['phone', 'address', 'birthday', 'social_line', 'social_zalo', 'social_facebook', 'send_mail'], 'trim'],
            ['phone', 'match', 'pattern' => '/^[0-9]+((\.|\s)?[0-9]+)*$/i'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'name'),
            'username' => Yii::t('app', 'username'),
            'email' => Yii::t('app', 'email'),
            'phone' => Yii::t('app', 'contact_phone'),
            'address' => Yii::t('app', 'address'),
            'birthday' => Yii::t('app', 'birthday'),
            'social_line' => Yii::t('app', 'social_line'),
            'social_zalo' => Yii::t('app', 'social_zalo'),
            'social_facebook' => Yii::t('app', 'social_facebook'),
            'password' => Yii::t('app', 'password'),
            'status' => Yii::t('app', 'status'),
            'send_mail' => Yii::t('app', 'send_mail_to_customer'),
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
        
        $user = new Customer();
        $user->name = $this->name;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->address = $this->address;
        $user->birthday = $this->birthday;
        $user->social_line = $this->social_line;
        $user->social_zalo = $this->social_zalo;
        $user->social_facebook = $this->social_facebook;
        $user->status = $this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if ($user->save()) {
            if ($this->send_mail == true) {
                $this->sendEmail();
            }
            return $user;
        }
        return false;
    }

    public function getUserStatus()
    {
        return Customer::getUserStatus();
    }

    public function sendEmail()
    {
        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
        $email = $this->email;
        return Yii::$app->mailer->compose('invite_customer', ['mail' => $this])
            ->setTo($email)
            ->setFrom([$adminEmail => Yii::$app->name])
            ->setSubject("[Kinggems][Notification email] Bạn nhận được thông báo từ " . Yii::$app->name)
            ->setTextBody('Bạn nhận được thông báo về thông tin tài khoản từ kinggems.us')
            ->send();
    }
}
