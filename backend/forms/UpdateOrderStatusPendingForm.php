<?php
namespace backend\forms;

use Yii;
use backend\models\Order;

class UpdateOrderStatusPendingForm extends ActionForm
{
    public $id;
    
    private $_order;

    public function rules()
    {
        return [
            [['id'], 'required'],
            ['id', 'validateOrder'],
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

    public function save()
    {
        if (!$this->validate()) return false;
        $model = $this->getOrder();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $model->on(Order::EVENT_AFTER_UPDATE, function ($event) {
                $order = $event->sender;
                $order->log(sprintf("Moved to pending"));
            });
            if (!$model->auth_key) $model->generateAuthKey();
            $model->status = Order::STATUS_PENDING;
            $model->pending_at = date('Y-m-d H:i:s');

            $result = $model->save();
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
