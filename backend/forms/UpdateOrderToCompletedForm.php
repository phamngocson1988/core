<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use backend\models\Supplier;
use backend\models\OrderSupplier;

class UpdateOrderToCompletedForm extends Model
{
    public $id;
    protected $_order;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateOrder'],
        ];
    }

    public function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }

    public function validateOrder($attribute, $params = []) 
    {
        $order = $this->getOrder();
        if (!$order) return $this->addError($attribute, 'Đơn hàng không tồn tại');
        if (!in_array($order->status, [
            Order::STATUS_PROCESSING,
            Order::STATUS_PARTIAL
        ])) return $this->addError($attribute, sprintf('Không thể hoàn tất đơn hàng khi ở trạng thái %s', $order->status));
        if ($order->doing_unit < $order->quantity) return $this->addError($attribute, 'Đơn hàng chưa được nhập đủ số lượng game');
    }
    
    public function move()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $order = $this->getOrder();
            $order->status = Order::STATUS_COMPLETED;
            $order->process_end_time = date('Y-m-d H:i:s');
            $order->completed_at = date('Y-m-d H:i:s');
            $order->process_duration_time = strtotime($order->completed_at) - strtotime($order->process_start_time);
            $order->on(Order::EVENT_AFTER_UPDATE, function($event) {
                $order = $event->sender;
                Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
                $order->log("Moved to completed");
                $order->send(
                    'admin_send_complete_order', 
                    sprintf("[KingGems] - Completed Order - Order #%s", $order->id), [
                        'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
                ]);
            });
            $order->save();

            // Complete supplier
            $supplier = $order->supplier;
            if ($supplier && !$supplier->isRequest()) {
                $supplier->status = OrderSupplier::STATUS_COMPLETED;
                $supplier->completed_at = date('Y-m-d H:i:s');
                $supplier->total_price = $supplier->price * $supplier->doing;
                $supplier->save();
            }
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }
}
