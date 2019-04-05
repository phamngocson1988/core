<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;

class RatingOrderForm extends Model
{
    public $auth_key;
    public $user_id;
    public $value;
    private $_order;

    public function rules()
    {
        return [
            [['auth_key', 'user_id', 'value'], 'required'],
            ['auth_key', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Order is not exist');
        } elseif ($order->customer_id != $this->user_id) {
            $this->addError($attribute, 'You cannot vote this order');
        }
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $order->rating = $this->value;
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
