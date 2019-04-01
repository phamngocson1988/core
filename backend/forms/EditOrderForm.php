<?php
namespace backend\forms;

use Yii;
use common\models\Order;
use common\models\User;

class EditOrderForm extends Order
{
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            ['customer_id', 'validateCustomer'],
        ];
    }

    public function validateCustomer($attribute, $params) 
    {
        // $customer = User::findOne($this->customer_id);
        if (!$this->customer) {
            $this->addError($attribute, 'Khách hàng không tồn tại');
        }
    }



    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $customer = $this->customer;
        $this->customer_name = $customer->name;
        $this->customer_email = $customer->email;
        $this->customer_phone = $customer->phone;
        return true;
    }
}