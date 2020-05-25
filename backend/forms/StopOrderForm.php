<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use backend\models\OrderSupplier;

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

        $stopStatus = [Order::STATUS_PROCESSING, Order::STATUS_PARTIAL];
        if (!in_array($order->status, $stopStatus)) {
            $this->addError('id', sprintf("Đơn hàng đang ở trạng thái %s nên không thể dừng", $order->status));
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
        $newQuantity = $this->quantity;
        $oldQuantity = $order->quantity;
        $refundQuantity = max(0, $oldQuantity - $newQuantity);
        $refundAmount = $order->price * $refundQuantity;
        $newSubTotalPrice = $order->price * $newQuantity;
        $newTotalPrice = $newSubTotalPrice - $order->total_discount + $order->total_fee + $order->total_tax;
        $newSubUnit = $order->sub_total_unit * $newQuantity / $order->quantity;
        $newTotalUnit = $newSubUnit + $order->promotion_unit;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);

            $diff = $this->quantity - $order->doing_unit;
            $order->status = Order::STATUS_COMPLETED;
            $order->doing_unit = $newQuantity;
            $order->quantity = $newQuantity;
            $order->sub_total_unit = $newSubUnit;
            $order->total_unit = $newTotalUnit;
            $order->total_price = $newTotalPrice;
            $order->sub_total_price = $newSubTotalPrice;
            $order->save();

            // Complete supplier
            $workingSupplier = $order->workingSupplier;
            if ($workingSupplier) {
                $completeSupplierCommand = $order->getSuppliers();
                $completedQuantity = $completeSupplierCommand->sum('doing');
                $workingSupplier->status = OrderSupplier::STATUS_COMPLETED;
                $workingSupplier->completed_at = date('Y-m-d H:i:s');
                $workingSupplier->doing = max(0, $newQuantity - $completedQuantity);
                $workingSupplier->total_price = $workingSupplier->price * $workingSupplier->doing;
                $workingSupplier->save();
            }

            // Topup user wallet
            $user = $order->customer;
            $user->topup($refundAmount, $order->id, sprintf("[Order #%s] Completed partially: %s/%s >>> Refund %s of the charge", $order->id, $newQuantity, $oldQuantity, $refundAmount));
            // Add to log
            $order->log(sprintf("Stop order when it is in %s / %s package(s) and refund %s", $newQuantity, $oldQuantity, $refundAmount));
            $order->send(
                'admin_notify_stop_order', 
                sprintf("[KingGems] - Completed Order - Order #%s", $order->id), [
                    'old_unit' => $oldQuantity, 
                    'new_unit' => $newQuantity, 
                    'refund_coin' => $refundAmount,
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

    public function stopbk()
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

            $diff = $this->quantity - $order->doing_unit;
            $order->status = Order::STATUS_COMPLETED;
            $order->doing_unit += $diff;
            $order->quantity = $this->quantity;
            $order->sub_total_unit = $newTotalUnit;
            $order->total_unit = $newTotalUnit;
            $order->total_price = $newTotalPrice;
            $order->save();

            // Complete supplier
            $supplier = $order->supplier;
            if ($supplier && !$supplier->isRequest()) {
                $supplier->status = OrderSupplier::STATUS_COMPLETED;
                $supplier->completed_at = date('Y-m-d H:i:s');
                $supplier->doing += $diff;
                $supplier->total_price = $supplier->price * $supplier->doing;
                $supplier->save();
            }

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
