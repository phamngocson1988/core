<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Order;
use frontend\models\Supplier;
use frontend\models\OrderSupplier;

class CompleteOrderForm extends Model
{
    public $auth_key;
    public $user_id;
    private $_order;

    public function rules()
    {
        return [
            [['auth_key', 'user_id'], 'required'],
            ['auth_key', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        } elseif (!$order->isCompletedOrder()) {
            $this->addError($attribute, 'Không thể chuyển trạng thái của đơn hàng hiện tại');
        } elseif ($order->customer_id != $this->user_id) {
            $this->addError($attribute, 'You cannot confirm this order');
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
                $orderSupplier->save();
                
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
            $this->addError('user_id', $e->getMessage());
            return false;
        }
    }

    public function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = Order::findOne(['auth_key' => $this->auth_key]);
        }
        return $this->_order;
    }
}
