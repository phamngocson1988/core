<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\Order;
use supplier\models\OrderSupplier;
use supplier\behaviors\OrderSupplierBehavior;

class RejectOrderSupplierForm extends Model
{
    public $order_id;
    public $supplier_id;

    protected $_order;
    protected $_order_supplier;

    public function rules()
    {
        return [
            [['order_id', 'supplier_id'], 'required'],
            ['order_id', 'validateOrder'],
        ];
    }

    public function getOrder()
    {
        if (!$this->_order) $this->_order = Order::findOne($this->order_id);
        return $this->_order;
    }

    public function validateOrder($attribute, $params = []) 
    {
        $order = $this->getOrder();
        if (!$order) return $this->addError($attribute, 'Đơn hàng không tồn tại');
        if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PROCESSING])) return $this->addError($attribute, 'Không thể nhận xử lý đơn hàng này');
        $supplier = $this->getOrderSupplier(); // OrderSupplier
        if (!$supplier) return $this->addError($attribute, 'Yêu cầu không tồn tại');
        if (!$supplier->isRequest()) return $this->addError($attribute, 'Yêu cầu không tồn tại');
        if ($supplier->supplier_id != $this->supplier_id) return $this->addError($attribute, 'Yêu cầu không tồn tại');
    }

    public function getOrderSupplier()
    {
        if (!$this->_order_supplier) {
            $order = $this->getOrder();
            if (!$order) return null;
            $order->attachBehavior('supplier', new OrderSupplierBehavior);
            $this->_order_supplier = $order->supplier;
        }
        return $this->_order_supplier;
    }

    public function reject()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $order = $this->getOrder();
            $supplier = $this->getOrderSupplier();
            $supplier->status = OrderSupplier::STATUS_REJECT;
            $supplier->rejected_at = date('Y-m-d H:i:s');
            $supplier->save();
            $order->supplier_id = null;
            $order->save();
            $transaction->commit();
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }

}
