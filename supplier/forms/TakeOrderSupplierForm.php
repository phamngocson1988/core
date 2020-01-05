<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\Order;
use supplier\models\OrderSupplier;
use supplier\models\Supplier;
use supplier\behaviors\OrderSupplierBehavior;

class TakeOrderSupplierForm extends Model
{
    public $order_id;
    public $supplier_id;

    protected $_order;
    protected $_supplier;
    protected $_order_supplier;
    public function rules()
    {
        return [
            [['order_id', 'supplier_id'], 'required'],
            ['order_id', 'validateOrder'],
            ['supplier_id', 'validateSupplier'],
        ];
    }

    public function getOrder()
    {
        if (!$this->_order) $this->_order = Order::findOne($this->order_id);
        return $this->_order;
    }

    public function getSupplier()
    {
        if (!$this->_supplier) {
            $this->_supplier = Supplier::findOne($this->supplier_id);
        }
        return $this->_supplier;
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

    public function validateSupplier($attribute, $params = []) 
    {
        $supplier = $this->getSupplier();
        $order = $this->getOrder();
        if (!$supplier) return $this->addError($attribute, 'Nhà cung cấp không tồn tại');
        if ($supplier->isDisabled()) return $this->addError($attribute, 'Nhà cung cấp hiện đang ngưng hoạt động');
        if (!$supplier->hasGame($order->game_id)) return $this->addError($attribute, 'Nhà cung cấp hiện không thể xử lý cho game này');

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

    public function approve()
    {
        $supplier = $this->getOrderSupplier();
        $supplier->status = OrderSupplier::STATUS_APPROVE;
        $supplier->approved_at = date('Y-m-d H:i:s');
        return $supplier->save();
    }

}
