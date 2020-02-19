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

class UpdateOrderToPartialForm extends Model
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
        if (!$supplier->isProcessing()) return $this->addError($attribute, 'Đơn hàng này không thể chuyển thành partial');
        if ($supplier->supplier_id != $this->supplier_id) return $this->addError($attribute, 'Yêu cầu không hợp lệ');
        if ($supplier->doing == $supplier->quantity) return $this->addError($attribute, 'Đơn hàng cần được chuyển qua trạng thái completed');

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

    
    public function move()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $supplier = $this->getOrderSupplier();
            $supplier->status = OrderSupplier::STATUS_COMPLETED;
            $supplier->completed_at = date('Y-m-d H:i:s');
            $supplier->total_price = $supplier->price * $supplier->doing;
            $supplier->save();

            $order = $this->getOrder();
            $order->status = Order::STATUS_PARTIAL;
            // $order->doing_unit += $supplier->doing;
            $order->on(Order::EVENT_AFTER_UPDATE, function($event) {
                $sender = $event->sender; // Order
                Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
                $sender->attachBehavior('log', OrderLogBehavior::className());
                $sender->attachBehavior('mail', OrderMailBehavior::className());
                $sender->log("Moved to partial");
                // $sender->send(
                //     'admin_send_complete_order', 
                //     sprintf("[KingGems] - Completed Order - Order #%s", $sender->id), [
                //         'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $sender->id], true),
                // ]);
            });

            $order->save();
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
}
