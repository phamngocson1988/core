<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\PaymentTransaction;
use common\models\PaymentCommitmentWallet;
use common\models\PaymentReality;
use common\models\PaymentCommitment;
use website\models\Order;

class UpdateTransactionForm extends Model
{
    public $id;
    public $payment_id;
    public $payment_data;
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
            ['payment_data', 'safe'],
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
        $reality = PaymentReality::find()->where([
            'payment_id' => $this->payment_id,
            'status' => PaymentReality::STATUS_CLAIMED
        ])->exists();
        if ($reality) {
            $this->addError($attribute, 'This payment id has been used.');
        }

        $commitment = PaymentCommitment::find()->where(['payment_id' => $this->payment_id])
        ->andWhere(['status' => [PaymentCommitment::STATUS_PENDING, PaymentCommitment::STATUS_APPROVED]])
        ->exists();
        if ($commitment) {
            $this->addError($attribute, 'This payment id has been used.');
        }
    }

    public function update()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $trn = $this->getTransaction();
            if (!$trn->payment_id) {
                $trn->payment_id = $this->payment_id;
            }
            $trn->payment_data = $this->payment_data;
            $trn->evidence = $this->evidence;
            $result = $trn->save();

            if ($result) {
                $commitment = PaymentCommitmentWallet::findOne(['object_key' => $trn->id]);
                if ($commitment) {
                    $commitment->payment_id = $trn->payment_id;
                    $commitment->evidence = $trn->evidence;

                    $commitment->on(PaymentCommitmentWallet::EVENT_AFTER_UPDATE, function($event) {
                        $model = $event->sender; //PaymentCommitmentWallet
                        if (!$model->payment_id) return;
                        $reality = PaymentReality::find()->where([
                            'payment_id' => $model->payment_id,
                            'status' => PaymentReality::STATUS_PENDING,
                        ])->one();
                        if (!$reality) return;
                        $approveTransactionService = new ApprovePaymentCommitmentForm([
                            'id' => $model->id,
                            'payment_reality_id' => $reality->id,
                            'note' => sprintf('Transaction is approved automatically, after updating payment id of %s', $model->getId()),
                            'confirmed_by' => $model->created_by,
                        ]);
                        $approveTransactionService->setReality($reality);
                        $approveTransactionService->setCommitment($model);
                        $approveTransactionService->approve();
                    });

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

