<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;

class CreateOrderForm extends Model
{
    public $total_price;
    public $customer_id;
    public $customer_name;
    public $customer_email;
    public $customer_phone;

    public function rules()
    {
        return [
            [['customer_id', 'customer_name', 'customer_email', 'customer_phone'], 'required'],
            ['total_price', 'default', 'value' => 0],
        ];
    }

    public function create()
    {
        if (!$this->validate()) return false;
        $order = new Order();
        $order->auth_key = $order->generateAuthKey();
        $order->total_price = $this->total_price;
        $order->customer_id = $this->customer_id;
        $order->customer_name = $this->customer_name;
        $order->customer_email = $this->customer_email;
        $order->customer_phone = $this->customer_phone;
        $order->status = Order::STATUS_TEMP;
        return $order->save();
    }
}

