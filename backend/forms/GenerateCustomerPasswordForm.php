<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Customer;

/**
 * GenerateCustomerPasswordForm
 */
class GenerateCustomerPasswordForm extends Model
{
    public $id;
    public $password;
    public $autoGenerate = false;
    public $sendMail = false;

    /** @var Customer **/
    protected $_customer;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateCustomer'],
            ['password', 'required', 'when' => function($model) {
                return $model->autoGenerate == false;
            }],
            [['autoGenerate', 'sendMail'], 'safe']
        ];
    }

    protected function getCustomer()
    {
        if ($this->_customer === null) {
            $this->_customer = Customer::findOne($this->id);
        }

        return $this->_customer;
    }

    public function validateCustomer($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $customer = $this->getCustomer();
            if (!$customer) {
                $this->addError($attribute, Yii::t('app', 'invalid_customer'));
            }
        }
    }

    public function setCustomer($customer)
    {
        if ($customer instanceof Customer) {
            $this->id = $customer->id;
            $this->_customer = $customer;
        }
    }

    public function generatePassword($length = 10)
    {
        if ($this->autoGenerate === true) {
            $security = new \yii\base\Security();
            $this->password = $security->generateRandomString($length);;
        }
    }

    public function generate()
    {
        if (!$this->validate()) {
            return false;
        }
        $this->generatePassword();
        $customer = $this->getCustomer();
        $customer->setPassword($this->password);
        $customer->generateAuthKey();
        $result = $customer->save();
        if ($result && $this->sendMail) {
            $this->send();
        }
        return $result;
    }
    public function send()
    {
        $customer = $this->getCustomer();
        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
        $email = $customer->email;
        return Yii::$app->mailer->compose('change_customer_password', ['mail' => $this])
            ->setTo($email)
            ->setFrom([$adminEmail => Yii::$app->name])
            ->setSubject("[Kinggems][Notification email] Bạn nhận được thông báo từ " . Yii::$app->name)
            ->setTextBody('Bạn nhận được thông báo về thông tin tài khoản từ kinggems.us')
            ->send();
    }
}
