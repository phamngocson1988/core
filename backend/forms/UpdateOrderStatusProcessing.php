<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;

class UpdateOrderStatusProcessing extends Model
{
    public $id;
    
    private $_order;

    public function rules()
    {
        return [
            [['id'], 'required'],
            ['id', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        } elseif (!$order->isPendingOrder()) {
            $this->addError($attribute, 'Không thể chuyển trạng thái của đơn hàng hiện tại');
        }
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $order->status = Order::STATUS_PROCESSING;
        return $order->save();

        // write to the log
    }

    public function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }
}
