<?php
namespace backend\forms;

use Yii;
use common\models\User;
use yii\base\Model;

/**
 * CreateCustomerForm
 */
class CreateCustomerForm extends Model
{
    public $email;
    public $password;
    public $name;
    public $company;
    public $tax_code;
    public $phone;
    public $address;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'company', 'tax_code'], 'required'],
            [['phone', 'address'], 'trim'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'name' => 'Người đại diện',
            'company' => 'Công ty',
            'phone' => 'Điện thoại',
            'address' => 'Địa chỉ',
            'tax_code' => 'Mã số thuế',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->name = $this->name;
        $user->username = $this->email;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->company = $this->company;
        $user->phone = $this->phone;
        $user->address = $this->address;
        $user->tax_code = $this->tax_code;
        $user->balance = 0;
        $user->status = User::STATUS_ACTIVE;
        return $user->save() ? $user : null;
    }
}
