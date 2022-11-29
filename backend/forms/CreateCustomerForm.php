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
            ['name', 'trim'],
            ['name', 'required'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => Yii::t('app', 'validate_alphanumeric')],
            ['username', 'unique', 'targetClass' => '\backend\models\User', 'message' => Yii::t('app', 'validate_username_unique')],


            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\User', 'message' => Yii::t('app', 'validate_email_unique')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['status', 'in', 'range' => array_keys(User::getUserStatus())],

            [['phone', 'country_code', 'address', 'birthday', 'send_mail', 'saler_id'], 'trim'],
            ['phone', 'match', 'pattern' => '/^[0-9]+((\.|\s)?[0-9]+)*$/i'],
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
        return $user->save() ? $user : null;
    }

    public function getUserStatus()
    {
        return User::getUserStatus();
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
