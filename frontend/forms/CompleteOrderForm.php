<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;

class CompleteOrderForm extends Model
{
    public $auth_key;
    
    private $_order;

    public function rules()
    {
        return [
            [['auth_key'], 'required'],
            ['auth_key', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        } elseif (!$order->isProcessingOrder()) {
            $this->addError($attribute, 'Không thể chuyển trạng thái của đơn hàng hiện tại');
        }
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $order->status = Order::STATUS_COMPLETED;
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
