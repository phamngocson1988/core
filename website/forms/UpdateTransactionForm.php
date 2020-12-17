<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\PaymentTransaction;
use website\models\Order;

class UpdateTransactionForm extends Model
{
    public $id;
    public $payment_id;
    public $evidence;

    private $_transaction;

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['payment_id', 'trim'],
            [['id', 'payment_id'], 'required'],
            ['id', 'validateTransaction'],
            ['evidence', 'safe'],
            ['payment_id', 'validatePaymentId'],
        ];
    }

    public function validateTransaction($attribute, $params = [])
    {
        $transaction = $this->getTransaction();
        if (!$transaction) {
            $this->addError($attribute, 'Payment transaction is not exist');
        } elseif ($transaction->user_id != Yii::$app->user->id) {
            $this->addError($attribute, 'Payment transaction is not exist');
        } elseif ($transaction->isCompleted()) {
            $this->addError($attribute, 'Payment transaction cannot be updated anymore.');
        }

    }

    public function validatePaymentId($attribute, $params = [])
    {
        if ($this->hasErrors()) return false;
        $order = Order::find()->where(['payment_id' => $this->payment_id])->one();
        if ($order) {
            return $this->addError($attribute, sprintf('Duplicated payment id with other order'));
        }
        $payment = PaymentTransaction::find()->where(['payment_id' => $this->payment_id])->one();
        if (!$payment) return true;
        if ($payment->id != $this->id) {
            $this->addError($attribute, sprintf('Duplicated payment id with transaction'));
            return false;
        }
    }

    public function update()
    {
        $transaction = $this->getTransaction();
        if (!$transaction->payment_id) {
            $transaction->payment_id = $this->payment_id;
        }
        $transaction->evidence = $this->evidence;
        return $transaction->save();
    }


    protected function getTransaction()
    {
        if (!$this->_transaction) {
            $this->_transaction = PaymentTransaction::findOne($this->id);
        }
        return $this->_transaction;
    }

    public function loadData()
    {
        $transaction = $this->getTransaction();
        $this->payment_id = $transaction->payment_id;
        $this->evidence = $transaction->evidence;
    }

}

