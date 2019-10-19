<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;

class StopOrderForm extends Order
{
    protected $_user;

	public function rules()
    {
        return [
            ['status', 'compare', 'compareValue' => Order::STATUS_PROCESSING, 'operator' => '==', 'type' => 'string'],
        ];
    }

    public function stop()
    {
        if (!$this->validate()) return false;
        // Calculate percent of work
        $percent = ceil($this->doing_unit / $this->quantity * 100);
        // Calculate percent of money
        $newTotalPrice = number_format($this->total_price * $percent / 100, 1);
        // Calculate remaining money
        $remainingPrice = $this->total_price - $newTotalPrice;
        // Calculate percent of coin
        // $newTotalUnit = $this->

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Update order total price, status
            $this->total_price = $newTotalPrice;
            $this->status = Order::STATUS_COMPLETED;
            $this->quantity = $this->doing_unit;
            $this->sub_total_unit = $this->doing_unit;
            $this->total_unit = $this->doing_unit;
            $this->save();
            // Add to complain
            // Topup user wallet
            $user = $this->customer;
            $user->topup($remainingPrice, null, "Refund for stopping order when it is in $percent percent");
            // Send mail notification
            $transaction->commit();
        } catch(Exception $e) {
            $transaction->rollback();
        }
    }
}
