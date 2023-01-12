<?php
namespace console\forms;

use Yii;
use common\models\Order;
use common\models\Supplier;
use common\models\OrderSupplier;

class ConfirmOrderForm extends ActionForm
{
    public $id;
    private $_order;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        } elseif (!$order->isCompletedOrder()) {
            $this->addError($attribute, 'Không thể chuyển trạng thái của đơn hàng hiện tại sang confirm');
        }
    }

    public function setOrder($order) 
    {
        $this->_order = $order;
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $order->status = Order::STATUS_CONFIRMED;
            $order->confirmed_at = date('Y-m-d H:i:s');
            $result = $order->save();
            $order->log("Moved to confirmed");

            // Update supplier wallet
            $command = OrderSupplier::find()
            ->where(['order_id' => $order->id])
            ->andWhere(['status' => OrderSupplier::STATUS_COMPLETED]);
            $suppliers = $command->all();
            foreach ($suppliers as $orderSupplier) {
                $supplier = Supplier::findOne($orderSupplier->supplier_id);
                if (!$supplier) continue;
                $orderSupplier->status = OrderSupplier::STATUS_CONFIRMED;
                $orderSupplier->confirmed_at = $order->confirmed_at;
                $orderSupplier->save();
                
                $amount = $orderSupplier->total_price;
                $source = 'order';
                $key = $order->id;
                $description = sprintf("Thanh toán cho đơn hàng #%s", $order->id);
                $supplier->topup($amount, $source, $key, $description);
            }

            Yii::$app->queue->push(new \common\queue\RunOrderCommissionJob(['id' => $order->id]));
            Yii::$app->queue->push(new \common\queue\UpdateUserFirstOrderJob(['order_id' => $order->id, 'user_id' => $order->customer_id]));
            $transaction->commit();
            return $result;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', $e->getMessage());
            return false;
        }
    }

    public function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }
}
