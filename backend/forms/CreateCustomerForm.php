<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Customer;

class CreateCustomerForm extends Model
{
    public $name;
    public $short_name;
    public $phone;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['short_name', 'trim'],
            ['short_name', 'string', 'min' => 2, 'max' => 255],

            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'unique', 'targetClass' => Customer::className(), 'message' => 'Số điện thoại bị trùng với một tài khoản khác'],
            ['phone', 'string', 'min' => 2, 'max' => 16],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Tên khách hàng',
            'short_name' => 'Tên thường gọi',
            'phone' => 'Số điện thoại',
        ];
    }
    public function create()
    {
        $user = new Customer();
        $user->name = $this->name;        
        $user->short_name = $this->short_name;
        $user->phone = $this->phone;
        
        return $user->save() ? $user : null;
    }
}
