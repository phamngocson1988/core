<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;

class StopOrderForm extends Model
{
    public $id;
    public $description;
    public $quantity;

    protected $_order;

	public function rules()
    {
        return [
            [['id', 'description', 'quantity'], 'required'],
            ['id', 'validateOrder'],
            ['description', 'trim'],
            ['quantity', 'validateQuantity'],
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError('id', 'This order is not exist.');
        }
        if (!$order->isProcessingOrder()) {
            $this->addError('id', 'This order cannot be stopped');
        }
    }

    public function validateQuantity($attribute, $params) 
    {
        $order = $this->getOrder();
        if ($this->quantity >= $order->quantity) {
            $this->addError('quantity', 'The quantity must be less than order quantity');
        }
    }

    public function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }

    public function stop()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        // Calculate percent of work
        $percent = ceil($this->quantity / $order->quantity * 100);
        
        // Calculate percent of coin
        $newTotalUnit = number_format($order->sub_total_unit * $percent / 100);

        // Calculate remaining money
        $newTotalPrice = number_format($order->total_price * $percent / 100, 1);
        $remainingPrice = $order->total_price - $newTotalPrice;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Update order total price, status
            $order->status = Order::STATUS_COMPLETED;
            $order->doing_unit = $this->quantity;
            $order->quantity = $this->quantity;
            $order->sub_total_unit = $newTotalUnit;
            $order->total_unit = $newTotalUnit;
            $order->save();
            // Topup user wallet
            $user = $order->customer;
            $user->topup($remainingPrice, null, sprintf("Refund for stopping order %s when it is in %s percent", $order->id, $percent));
            // Add to complain
            // Send mail notification
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', 'This order has some errors.');
            return false;
        }
    }
}
