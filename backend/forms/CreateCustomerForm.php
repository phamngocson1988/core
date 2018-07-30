<?php
namespace backend\forms;

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

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\backend\models\Customer', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['status', 'in', 'range' => array_keys(Customer::getUserStatus())],

            [['phone', 'address', 'birthday', 'social_line', 'social_zalo', 'social_facebook'], 'trim']
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
        return $user->save() ? $user : null;
    }

    public function getUserStatus()
    {
        return Customer::getUserStatus();
    }
}
