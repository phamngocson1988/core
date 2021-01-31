<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\PaymentTransaction;
use common\models\PaymentCommitmentWallet;
use common\models\PaymentReality;
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
            // ['payment_id', 'validatePaymentId'],
            ['payment_id', 'unique', 'targetClass' => PaymentReality::className(), 'message' => 'This payment id has been used.'],
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

    // public function validatePaymentId($attribute, $params = [])
    // {
    //     if ($this->hasErrors()) return false;
    //     $order = Order::find()
    //     ->where(['payment_id' => $this->payment_id])
    //     ->andWhere(['<>', 'status', Order::STATUS_DELETED])
    //     ->one();
    //     if ($order) {
    //         return $this->addError($attribute, sprintf('Duplicated payment id with other order'));
    //     }
    //     $payment = PaymentTransaction::find()
    //     ->where(['payment_id' => $this->payment_id])
    //     ->andWhere(['<>', 'status', PaymentTransaction::STATUS_DELETED])
    //     ->one();
    //     if (!$payment) return true;
    //     if ($payment->id != $this->id) {
    //         $this->addError($attribute, sprintf('Duplicated payment id with transaction'));
    //         return false;
    //     }
    // }

    public function update()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $trn = $this->getTransaction();
            if (!$trn->payment_id) {
                $trn->payment_id = $this->payment_id;
            }
            $trn->evidence = $this->evidence;
            $result = $trn->save();

            if ($result) {
                $commitment = PaymentCommitmentWallet::findOne(['object_key' => $trn->id]);
                if ($commitment && !$commitment->payment_id) {
                    $commitment->payment_id = $trn->payment_id;
                    $commitment->evidence = $trn->evidence;
                    $commitment->save();
                }
            }
            $transaction->commit();
            return $result;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('payment_id', $e->getMessage());
            return false;
        }
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

