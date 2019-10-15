<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;

class CompleteOrderForm extends Model
{
    public $auth_key;
    public $user_id;
    private $_order;

    public function rules()
    {
        return [
            [['auth_key', 'user_id'], 'required'],
            ['auth_key', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        } elseif (!$order->isCompletedOrder()) {
            $this->addError($attribute, 'Không thể chuyển trạng thái của đơn hàng hiện tại');
        } elseif ($order->customer_id != $this->user_id) {
            $this->addError($attribute, 'You cannot vote this order');
        }
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $order->status = Order::STATUS_CONFIRMED;
        return $order->save();
    }

    public function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = Order::findOne(['auth_key' => $this->auth_key]);
        }
        return $this->_order;
    }
}
