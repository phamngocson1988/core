<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;

class UpdateOrderStatusPending extends Model
{
    public $id;
    public $description;
    
    private $_order;

    public function rules()
    {
        return [
            [['id', 'description'], 'required'],
            ['id', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        } elseif (!$order->isVerifyingOrder()) {
            $this->addError($attribute, 'Không thể chuyển trạng thái của đơn hàng hiện tại');
        }
    }

    public function save()
    {
        if (!$this->validate()) return fasle;
        $order = $this->getOrder();
        $order->status = Order::STATUS_PENDING;
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
