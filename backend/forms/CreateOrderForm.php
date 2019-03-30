<?php
namespace backend\forms;

use Yii;
use common\models\Order;
use common\models\User;

class CreateOrderForm extends Order
{
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            ['customer_id', 'validateCustomer'],
            ['saler_id', 'trim'],
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
        $this->generateAuthKey();
        $this->status = self::STATUS_VERIFYING;
        $this->saler_id = ($this->saler_id) ? $this->saler_id : Yii::$app->user->id;
        $customer = $this->customer;
        $this->customer_name = $customer->name;
        $this->customer_email = $customer->email;
        $this->customer_phone = $customer->phone;
        return true;
    }

    // public function afterSave()
    // {

    //     Yii::$app->syslog->log('assign_role', 'assign role to user', [
    //         'user_id' => $user->id,
    //         'username' => $user->username,
    //         'role' => $role->name
    //     ]);
    // }
}