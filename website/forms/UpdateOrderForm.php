<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Order;
use website\models\PaymentTransaction;
use common\models\PaymentCommitmentOrder;
use common\models\PaymentReality;

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
        $reality = PaymentReality::find()->where([
            'payment_id' => $this->payment_id,
            'status' => PaymentReality::STATUS_CLAIMED
        ])->exists();
        if ($reality) {
            $this->addError($attribute, 'This payment id has been used.');
        }
    }

    public function update()
    {
        $order = $this->getOrder();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            if (!$order->payment_id) {
                $order->payment_id = $this->payment_id;
            }
            $order->evidence = $this->evidence;
            $result = $order->save();

            // update commitment
            if ($result) {
                $commitment = PaymentCommitmentOrder::findOne(['object_key' => $order->id]);
                if ($commitment) {
                    $commitment->payment_id = $order->payment_id;
                    $commitment->evidence = $order->evidence;

                    $commitment->on(PaymentCommitmentOrder::EVENT_AFTER_UPDATE, function($event) {
                        $model = $event->sender; //PaymentCommitmentOrder
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

