<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use backend\models\User;
/**
 * AssignSalerToOrderForm
 */
class AssignSalerToOrderForm extends Model
{
    public $order_id;
    public $user_id;
    public $force_update = false;

    protected $allow_roles = ['saler', 'saler_manager'];
    private $_user;
    private $_order;

    public function rules()
    {
        return [
            [['order_id', 'user_id'], 'required'],
            ['order_id', 'validateOrder'],
            ['user_id', 'validateUser'],
            ['force_update', 'safe']
        ];
    }

    public function run()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $order->saler_id = $this->user_id;
        return $order->save();
    }

    public function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = Order::findOne($this->order_id);
        }
        return $this->_order;
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            return $this->addError($attribute, 'Order is not exist');
        }
        if ($order->isCompletedOrder() || $order->isConfirmedOrder()) {
            return $this->addError($attribute, sprintf("Order %s is finished", $this->order_id));
        }
        if ($order->saler_id && !$this->force_update) {
            return $this->addError($attribute, "The order was taken by another AM member");
        }
    }

    public function validateUser($attribute, $params)
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($this->user_id);
        $roleNames = array_keys($roles);
        $intersect = array_intersect($roleNames, $this->allow_roles);
        if (!count($intersect)) {
            return $this->addError($attribute, "Assignee is not in AM team");
        }
    }
}
