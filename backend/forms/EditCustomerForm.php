<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Customer;
use common\components\helpers\FormatConverter;

/**
 * EditCustomerForm
 */
class EditCustomerForm extends Model
{
    public $id;
    public $name;
    public $username;
    public $email;
    public $phone;
    public $address;
    public $birthday;
    public $social_line;
    public $social_zalo;
    public $social_facebook;
    public $status;

    protected $_customer;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'trim'],
            ['id', 'required'],
            ['id', 'validateCustomer'],

            ['name', 'trim'],
            ['name', 'required'],

            ['status', 'in', 'range' => array_keys(Customer::getUserStatus())],

            [['phone', 'address', 'birthday', 'social_line', 'social_zalo', 'social_facebook'], 'trim']
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
            'status' => Yii::t('app', 'status'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return Customer|null the saved model or null if saving fails
     */
    public function save()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = $this->getCustomer();
        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->address = $this->address;
        $user->birthday = FormatConverter::convertToTimeStamp($this->birthday);
        $user->social_line = $this->social_line;
        $user->social_zalo = $this->social_zalo;
        $user->social_facebook = $this->social_facebook;
        $user->status = $this->status;
        return $user->save() ? $user : null;
    }

    public function getUserStatus()
    {
        return Customer::getUserStatus();
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

    protected function getCustomer()
    {
        if ($this->_customer === null) {
            $this->_customer = Customer::findOne($this->id);
        }

        return $this->_customer;
    }

    public function loadData($id)
    {
        $this->id = $id;
        $customer = $this->getCustomer();
        $this->name = $customer->name;
        $this->username = $customer->username;
        $this->email = $customer->email;
        $this->phone = $customer->phone;
        $this->address = $customer->address;
        $this->birthday = FormatConverter::convertToDate($customer->birthday);
        $this->social_line = $customer->social_line;
        $this->social_zalo = $customer->social_zalo;
        $this->social_facebook = $customer->social_facebook;
        $this->status = $customer->status;
    }
}