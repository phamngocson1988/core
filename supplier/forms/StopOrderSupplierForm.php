<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\Order;
use supplier\models\OrderSupplier;
use supplier\behaviors\OrderSupplierBehavior;

class StopOrderSupplierForm extends Model
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
        if (!in_array($order->status, [Order::STATUS_PROCESSING])) return $this->addError($attribute, 'Không thể dừng xử lý đơn hàng này');
        $supplier = $this->getOrderSupplier(); // OrderSupplier
        if (!$supplier) return $this->addError($attribute, 'Yêu cầu không tồn tại');
        if (!$supplier->isApprove()) return $this->addError($attribute, 'Yêu cầu không tồn tại');
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

    public function stop()
    {
        $supplier = $this->getOrderSupplier();
        $supplier->status = OrderSupplier::STATUS_STOP;
        $supplier->stopped_at = date('Y-m-d H:i:s');
        return $supplier->save();
    }

}
