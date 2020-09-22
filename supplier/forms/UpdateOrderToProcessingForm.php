<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\Order;
use supplier\models\Supplier;
use supplier\models\OrderSupplier;
use supplier\behaviors\OrderSupplierBehavior;
use supplier\behaviors\OrderLogBehavior;
use supplier\behaviors\OrderMailBehavior;

class UpdateOrderToProcessingForm extends Model
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

    public function validateRequest($attribute, $params = []) 
    {
        $supplier = $this->getOrderSupplier(); // OrderSupplier
        if (!$supplier) return $this->addError($attribute, 'Yêu cầu không tồn tại');
        if (!$supplier->isApprove()) return $this->addError($attribute, 'Yêu cầu không hợp lệ');
        if ($supplier->supplier_id != $this->supplier_id) return $this->addError($attribute, 'Yêu cầu không hợp lệ');

        $order = $this->getOrder();
        if (!$order) return $this->addError($attribute, 'Đơn hàng không tồn tại');
        if (!in_array($order->status, [
            Order::STATUS_PENDING, 
            Order::STATUS_PROCESSING,
            Order::STATUS_PARTIAL
        ])) return $this->addError($attribute, sprintf('Đơn hàng có mã số %s không thể xử lý. Hãy báo lỗi này đến nhân viên đơn hàng.', $order->id));
        
    }

    public function validateSupplier($attribute, $params = []) 
    {
        $supplier = $this->getSupplier();
        $order = $this->getOrder();
        if (!$supplier) return $this->addError($attribute, 'Nhà cung cấp không tồn tại');
        if ($supplier->isDisabled()) return $this->addError($attribute, 'Nhà cung cấp hiện đang ngưng hoạt động');
        if (!$supplier->hasGame($order->game_id)) return $this->addError($attribute, 'Nhà cung cấp hiện không thể xử lý cho game này');

    }

    
    public function move()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $supplier = $this->getOrderSupplier();
            $supplier->status = OrderSupplier::STATUS_PROCESSING;
            $supplier->processing_at = date('Y-m-d H:i:s');
            $supplier->save();

            $order = $this->getOrder();
            if ($order->isPendingOrder()) {
                $order->status = Order::STATUS_PROCESSING;
                $order->state = new \yii\db\Expression('NULL');
                $order->process_start_time = date('Y-m-d H:i:s');
                $order->processing_at = $order->processing_at ? $order->processing_at : date('Y-m-d H:i:s');
                $order->on(Order::EVENT_AFTER_UPDATE, function($event) {
                    $sender = $event->sender; // Order
                    Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
                    $sender->log("Moved to processing");
                });

                $order->save();
            }
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
}
