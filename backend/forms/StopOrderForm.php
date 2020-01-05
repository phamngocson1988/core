<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use backend\behaviors\OrderLogBehavior;
use backend\behaviors\OrderMailBehavior;

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
            ['quantity', 'number']
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
        $remainingPercent = 100 - $percent;
        
        // Calculate percent of coin
        $oldUnit = $order->sub_total_unit;
        $newTotalUnit = ceil($order->sub_total_unit * $percent / 100);

        // Calculate remaining money
        $newTotalPrice = ceil($order->total_price * $percent / 100);
        $remainingPrice = $order->total_price - $newTotalPrice;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
            // Update order total price, status
            $order->attachBehavior('log', OrderLogBehavior::className());
            $order->attachBehavior('mail', OrderMailBehavior::className());

            $order->status = Order::STATUS_COMPLETED;
            $order->doing_unit = $this->quantity;
            $order->quantity = $this->quantity;
            $order->sub_total_unit = $newTotalUnit;
            $order->total_unit = $newTotalUnit;
            $order->save();
            // Topup user wallet
            $user = $order->customer;
            $user->topup($remainingPrice, $order->id, sprintf("[Order #%s] Completed partially: %s/%s >>> Refund %s &percnt; of the charge", $order->id, $newTotalUnit, $oldUnit, $remainingPercent));
            // Add to log
            $order->log(sprintf("Stop order when it is in %s percent and refund %s", $percent, $remainingPrice));
            $order->send(
                'admin_notify_stop_order', 
                sprintf("[KingGems] - Completed Order - Order #%s", $order->id), [
                    'old_unit' => $oldUnit, 
                    'new_unit' => $newTotalUnit, 
                    'refund_coin' => $remainingPrice,
                    'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true)
                ]);
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
