<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Order;
use website\models\PaymentTransaction;

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
            ['payment_id', 'trim'],
            [['id', 'payment_id'], 'required'],
            ['id', 'validateOrder'],
            ['evidence', 'safe'],
            ['payment_id', 'validatePaymentId'],
        ];
    }

    public function validateOrder($attribute, $params = [])
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Order is not exist');
        } elseif ($order->customer_id != Yii::$app->user->id) {
            $this->addError($attribute, 'Order is not exist');
        } elseif (!$order->isVerifyingOrder()) {
            $this->addError($attribute, 'Order cannot be updated anymore.');
        }

    }

    public function validatePaymentId($attribute, $params = [])
    {
        if ($this->hasErrors()) return false;
        $payment = PaymentTransaction::find()
        ->where(['payment_id' => $this->payment_id])
        ->andWhere(['<>', 'status', PaymentTransaction::STATUS_DELETED])
        ->one();
        if ($payment) {
            return $this->addError($attribute, sprintf('Duplicated payment id with other transaction'));
        }

        $order = Order::find()
        ->where(['payment_id' => $this->payment_id])
        ->andWhere(['<>', 'status', Order::STATUS_DELETED])
        ->one();
        if (!$order) return true;
        if ($order->id != $this->id) {
            $this->addError($attribute, sprintf('Duplicated payment id with other order'));
            return false;
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

