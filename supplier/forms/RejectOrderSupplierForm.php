<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\Order;
use supplier\models\Supplier;
use supplier\models\OrderSupplier;

class RejectOrderSupplierForm extends Model
{
    public $id;
    public $supplier_id;

    protected $_order;
    protected $_order_supplier;
    protected $_supplier;
    
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
        if (!$supplier->canBeRejected()) return $this->addError($attribute, 'Không thể từ chối đơn hàng');
        if ($supplier->supplier_id != $this->supplier_id) return $this->addError($attribute, 'Yêu cầu không hợp lệ cho nhà cung cấp');

        $order = $this->getOrder();
        if (!$order) return $this->addError($attribute, 'Đơn hàng không tồn tại');
        // if (!in_array($order->status, [
        //     Order::STATUS_PENDING, 
        //     Order::STATUS_PROCESSING,
        //     Order::STATUS_PARTIAL
        // ])) return $this->addError($attribute, 'Không thể từ chối xử lý đơn hàng này');
        
    }

    public function validateSupplier($attribute, $params = []) 
    {
        $supplier = $this->getSupplier();
        $order = $this->getOrder();
        if (!$supplier) return $this->addError($attribute, 'Nhà cung cấp không tồn tại');
        if ($supplier->isDisabled()) return $this->addError($attribute, 'Nhà cung cấp hiện đang ngưng hoạt động');
        if (!$supplier->hasGame($order->game_id)) return $this->addError($attribute, 'Bạn cần đăng ký game này trước khi xử lý');

    }

    public function getOrderSupplier()
    {
        if (!$this->_order_supplier) {
            $this->_order_supplier = OrderSupplier::findOne($this->id);
        }
        return $this->_order_supplier;
    }

    public function reject()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $order = $this->getOrder();
            $supplier= $this->getSupplier();
            $orderSupplier = $this->getOrderSupplier();
            $orderSupplier->status = OrderSupplier::STATUS_REJECT;
            $orderSupplier->rejected_at = date('Y-m-d H:i:s');
            $orderSupplier->save();


            $order->supplier_id = null;
            $order->save();
            $order->log(sprintf("Nhà cung cấp %s từ chối đơn hàng", $supplier->user->name, $this->supplier_id));
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }

}
