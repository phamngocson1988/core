<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use backend\events\OrderEventHandler;
use backend\forms\RetakeOrderSupplierForm;
use backend\components\notifications\OrderNotification;

class DenyCancelOrder extends Model
{
    public $id;
    private $_order;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        }
        if (!$order->hasCancelRequest()) {
            $this->addError($attribute, sprintf('Đơn hàng không có yêu cầu hủy'));
        }
        $orderSupplier = $order->workingSupplier;
        if ($orderSupplier) {
            $user = $orderSupplier->user;
            $this->addError($attribute, sprintf('Đơn hàng vẫn đang được xử lý bởi nhà cung cấp %s (#%s)', $user->name, $user->id));
        }
    }

    public function deny()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            // $order->on(Order::EVENT_AFTER_UPDATE, [OrderEventHandler::className(), 'sendMailDeleteOrder']);
            $order->on(Order::EVENT_AFTER_UPDATE, function($event) {
                $model = $event->sender;
                $model->log(sprintf("Disapproved to be cancelled when status is %s", $model->status));
                $model->pushNotification(OrderNotification::NOTIFY_CUSTOMER_CANCELLATION_DENIED_ORDER, $model->customer_id);
            });
            $order->request_cancel = 0;
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
