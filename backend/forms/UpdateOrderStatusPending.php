<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;

class UpdateOrderStatusPending extends Model
{
    public $id;
    public $payment_method;
    public $payment_id;
    
    private $_order;

    public function rules()
    {
        return [
            [['id', 'payment_method', 'payment_id'], 'required'],
            ['id', 'validateOrder'],
            ['payment_id', 'validatePaymentId']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        } elseif (!$order->isVerifyingOrder()) {
            $this->addError($attribute, 'Không thể chuyển trạng thái của đơn hàng hiện tại thành pending');
        }
    }

    public function validatePaymentId($attribute, $params = []) 
    {
        if (!$this->payment_id) return;
        $count = Order::find()
        ->where(['payment_id' => $this->payment_id])
        ->andWhere(['payment_method' => $this->payment_method])->count();
        if ($count) {
            $this->addError($attribute, 'SỐ LỆNH GIAO DỊCH đã được sử dụng');
        }
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $order->on(Order::EVENT_AFTER_UPDATE, function ($event) {
                $order = $event->sender; // Order
                // Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
                // $order->send(
                //     'admin_send_pending_order', 
                //     sprintf("Order confirmation - %s", $order->id), [
                //         'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
                // ]);
                $order->log(sprintf("Moved to pending with payment_id: %s", $order->payment_id));
            });

            $order->status = Order::STATUS_PENDING;
            $order->payment_method = $this->payment_method;
            $order->payment_id = $this->payment_id;
            if (!$order->auth_key) $order->generateAuthKey();
            $result = $order->save();
            $transaction->commit();
            return $result;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', $e->getMessage());
            return false;
        }
    }

    public function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }
}
