<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Order;
use website\models\OrderSupplier;
use website\models\Supplier;

class ConfirmOrderForm extends Model
{
    public $id;

    private $_order;

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateOrder'],
        ];
    }

    public function validateOrder($attribute, $params = [])
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Order is not exist');
        } elseif ($order->customer_id != Yii::$app->user->id) {
            $this->addError($attribute, 'Order is not exist');
        } elseif (!$order->isCompletedOrder()) {
            $this->addError($attribute, 'Order cannot be confirmed.');
        }

    }

    public function confirm()
    {
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


    protected function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }
}

