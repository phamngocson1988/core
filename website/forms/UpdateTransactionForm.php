<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\PaymentTransaction;

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
            [['id', 'payment_id'], 'required'],
            ['id', 'validateTransaction'],
            ['evidence', 'safe'],
        ];
    }

    public function validateTransaction($attribute, $params = [])
    {
        $transaction = $this->getTransaction();
        if (!$transaction) {
            $this->addError($attribute, 'Payment transaction is not exist');
        }
        if ($transaction->user_id != Yii::$app->user->id) {
            $this->addError($attribute, 'Payment transaction is not exist');
        }
    }

    public function update()
    {
        $transaction = $this->getTransaction();
        $transaction->payment_id = $this->payment_id;
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

