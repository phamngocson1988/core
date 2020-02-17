<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use backend\models\Supplier;
use backend\models\OrderSupplier;

class ConfirmOrderForm extends Model
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

    public function save()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $order->status = Order::STATUS_CONFIRMED;
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
                $amount = $orderSupplier->total_price;
                $source = 'order';
                $key = $order->id;
                $description = sprintf("Thanh toán cho đơn hàng #%s", $order->id);
                $supplier->topup($amount, $source, $key, $description);
            }

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
