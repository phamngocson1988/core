<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Order;

class UpdateOrderForm extends Model
{
    public $id;
    public $payment_id;
    public $evidence;

    private $_order;

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'payment_id'], 'required'],
            ['id', 'validateOrder'],
            ['evidence', 'safe'],
        ];
    }

    public function validateOrder($attribute, $params = [])
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Order is not exist');
        } elseif ($order->customer_id != Yii::$app->user->id) {
            $this->addError($attribute, 'Order is not exist');
        } elseif (!$order->isVerifying()) {
            $this->addError($attribute, 'Order cannot be updated anymore.');
        }

    }

    public function update()
    {
        $order = $this->getOrder();
        if (!$order->payment_id) {
            $order->payment_id = $this->payment_id;
        }
        $order->evidence = $this->evidence;
        return $order->save();
    }


    protected function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }

    public function loadData()
    {
        $order = $this->getOrder();
        $this->payment_id = $order->payment_id;
        $this->evidence = $order->evidence;
    }

}

