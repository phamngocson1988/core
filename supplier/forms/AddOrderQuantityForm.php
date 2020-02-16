<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\Order;
use supplier\models\Supplier;
use supplier\models\OrderSupplier;
use supplier\behaviors\OrderSupplierBehavior;

class AddOrderQuantityForm extends Model
{
    public $id;
    public $supplier_id;
    public $doing;

    protected $_order_supplier;
    protected $_order;
    protected $_supplier;
    protected $_final_doing;

    public function rules()
    {
        return [
            [['id', 'supplier_id', 'doing'], 'required'],
            ['id', 'validateRequest'],
            ['supplier_id', 'validateSupplier'],
            ['doing', 'number'],
            ['doing', 'validateQuantity']
        ];
    }

    public function getOrderSupplier()
    {
        if (!$this->_order_supplier) {
            $this->_order_supplier = OrderSupplier::findOne($this->id);
        }
        return $this->_order_supplier;
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

    public function getFinalDoing() 
    {
        if (!$this->_final_doing) {
            $supplier = $this->getOrderSupplier();
            $this->_final_doing = $supplier->doing + $this->doing;
        }
        return $this->_final_doing;
    }

    public function validateRequest($attribute, $params = []) 
    {
        $supplier = $this->getOrderSupplier(); // OrderSupplier
        if (!$supplier) return $this->addError($attribute, 'Yêu cầu không tồn tại');
        if (!$supplier->isProcessing()) return $this->addError($attribute, 'Bạn không thể nạp thêm số lượng cho đơn hàng này');
        if ($supplier->supplier_id != $this->supplier_id) return $this->addError($attribute, 'Bạn không có quyền xử lý đơn hàng này');

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

    public function validateQuantity($attribute, $params = [])
    {
        $supplier = $this->getOrderSupplier();
        if ($this->getFinalDoing() > $supplier->quantity) {
            $this->addError($attribute, 'Số lượng nạp vượt quá yêu cầu');
        }
    }

    public function add()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $supplier = $this->getOrderSupplier();
            $supplier->doing = $this->getFinalDoing();
            $supplier->save();

            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
}
