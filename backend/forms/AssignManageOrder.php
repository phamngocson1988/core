<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;
use common\models\User;

class AssignManageOrder extends Model
{
    public $user_id;
    public $order_id;
    
    private $_order;
    private $_user;

    public function rules()
    {
        return [
            [['user_id', 'order_id'], 'required'],
            ['user_id', 'validateUser']
            ['order_id', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        }
    }

    public function validateUser($attribute, $params)
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, 'Nhân viên không tồn tại');
        } elseif (!$user->can('saler') || !$user->can('handler')) {
            $this->addError($attribute, 'Nhân viên không có đủ quyền hạn để quản lý đơn hàng');
        }
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        if ($order->isVerifyingOrder()) {
            $order->saler_id = $this->user_id;
        } else {
            $order->handler_id = $this->user_id
        }
        return $order->save();
    }

    public function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = Order::findOne($this->order_id);
        }
        return $this->_order;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }
}
