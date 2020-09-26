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
    public $id;
    public $supplier_id;

    protected $_order;
    protected $_supplier;
    protected $_order_supplier;
    public function rules()
    {
        return [
            [['id', 'supplier_id'], 'required'],
            ['id', 'validateRequest'],
            ['supplier_id', 'validateSupplier'],
        ];
    }

    public function getOrder()
    {
        $supplier = $this->getOrderSupplier(); // OrderSupplier
        if (!$this->_order) $this->_order = $supplier->order;
        return $this->_order;
    }

    public function getSupplier()
    {
        if (!$this->_supplier) {
            $this->_supplier = Supplier::findOne($this->supplier_id);
        }
        return $this->_supplier;
    }

    public function validateRequest($attribute, $params = []) 
    {
        $supplier = $this->getOrderSupplier(); // OrderSupplier
        if (!$supplier) return $this->addError($attribute, 'Yêu cầu không tồn tại');
        if (!$supplier->isRequest()) return $this->addError($attribute, 'Yêu cầu không hợp lệ');
        if ($supplier->supplier_id != $this->supplier_id) return $this->addError($attribute, 'Yêu cầu không hợp lệ');

        $order = $this->getOrder();
        if (!$order) return $this->addError($attribute, 'Đơn hàng không tồn tại');
        if (!in_array($order->status, [
            Order::STATUS_PENDING, 
            Order::STATUS_PROCESSING,
            Order::STATUS_PARTIAL
        ])) return $this->addError($attribute, 'Không thể nhận xử lý đơn hàng này');
        
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
            $this->_order_supplier = OrderSupplier::findOne($this->id);
        }
        return $this->_order_supplier;
    }

    public function approve()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $orderSupplier = $this->getOrderSupplier();
            $orderSupplier->status = OrderSupplier::STATUS_APPROVE;
            $orderSupplier->approved_at = date('Y-m-d H:i:s');
            $result = $orderSupplier->save();

            $order = $this->getOrder();
            $supplier = $this->getSupplier();
            $order->approved_at = $order->approved_at ? $order->approved_at : date('Y-m-d H:i:s');
            $order->save();
            $order->log(sprintf("Nhà cung cấp %s chấp nhận đơn hàng", $supplier->user->name, $this->supplier_id));

            $transaction->commit();
            return $result;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', $e->getMessage());
            return false;
        }
    }

}
