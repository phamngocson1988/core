<?php
namespace frontend\models;

use Yii;

/**
 * Order model
 */
class Order extends \common\models\Order
{
	const SCENARIO_CREATE = 'create';
	const SCENARIO_PAYMENT = 'payment';

	public function scenarios()
    {
    	$scenarios = parent::scenarios();
        return array_merge($scenarios, [
            self::SCENARIO_CREATE => ['total_price', 'customer_id', 'customer_name', 'customer_email', 'customer_phone', 'saler_id'],
            self::SCENARIO_PAYMENT => ['payment_id', 'paygate'],
        ]);
    }

    public function rules()
    {
        return [
            [['customer_id', 'customer_name', 'customer_email', 'customer_phone'], 'required'],
            ['total_price', 'default', 'value' => 0],
        ];
    }

    // public function save($runValidation = true, $attributeNames = null)
    // {
    //     if (!$this->validate()) return false;
    //     $order = new Order();
    //     $order->auth_key = $order->generateAuthKey();
    //     $order->total_price = $this->total_price;
    //     $order->customer_id = $this->customer_id;
    //     $order->customer_name = $this->customer_name;
    //     $order->customer_email = $this->customer_email;
    //     $order->customer_phone = $this->customer_phone;
    //     $order->status = Order::STATUS_TEMP;
    //     return $order->save();
    // }
}