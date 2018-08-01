<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Customer;
use common\components\helpers\FormatConverter;

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
        $user->birthday = FormatConverter::convertToTimeStamp($this->birthday);
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
