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
use supplier\components\notifications\OrderNotification;

class UpdateOrderToCompletedForm extends Model
{
    public $id;
    public $supplier_id;
    public $doing;

    protected $_order;
    protected $_supplier;
    protected $_order_supplier;

    public function rules()
    {
        return [
            [['id', 'supplier_id'], 'required'],
            ['id', 'validateRequest'],
            ['supplier_id', 'validateSupplier'],

            ['doing', 'required'],
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
        if (!$this->_order) {
            $this->_order = Order::findOne($supplier->order_id);
        }
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
        if (!$supplier->isProcessing()) return $this->addError($attribute, 'Đơn hàng này không thể chuyển thành completed');
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

    public function validateQuantity($attribute, $params = [])
    {
        $supplier = $this->getOrderSupplier();
        if ($this->doing > $supplier->quantity) {
            $this->addError($attribute, 'Số lượng nạp vượt quá yêu cầu');
        }
        if ($this->doing < 0) {
            $this->addError($attribute, 'Số lượng nạp không hợp lệ');
        }
    }

    
    public function move()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $supplier = $this->getOrderSupplier();
        try {
            $percent = (int)(($this->doing / $supplier->quantity) * 100);
            $supplier->status = OrderSupplier::STATUS_COMPLETED;
            $supplier->completed_at = date('Y-m-d H:i:s');
            $supplier->doing = $this->doing;
            $supplier->percent = max($supplier->percent, $percent);
            $supplier->total_price = $supplier->price * $this->doing;
            $supplier->save();

            $order = $this->getOrder();
            $order->doing_unit += $this->doing;
            $isCompleted = $this->doing == $supplier->quantity;
            if ($isCompleted) {
                $order->status = Order::STATUS_COMPLETED;
                $order->process_end_time = date('Y-m-d H:i:s');
                $order->completed_at = date('Y-m-d H:i:s');
                $order->process_duration_time = strtotime($order->process_end_time) - strtotime($order->process_start_time);
                $order->on(Order::EVENT_AFTER_UPDATE, function($event) {
                    $sender = $event->sender; // Order
                    Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
                    $sender->log("Moved to completed");
                    $sender->pushNotification(OrderNotification::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE, $sender->customer_id);
                    // $sender->send(
                    //     'admin_send_complete_order', 
                    //     sprintf("[KingGems] - Completed Order - Order #%s", $sender->id), [
                    //         'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $sender->id], true),
                    // ]);
                });
            } else {
                $order->status = Order::STATUS_PARTIAL;
                $order->on(Order::EVENT_AFTER_UPDATE, function($event) {
                    $sender = $event->sender; // Order
                    $sender->log("Moved to partial");
                });
            }
            
            $order->save();
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
}
