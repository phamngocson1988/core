<?php

namespace website\forms;

use Yii;
use common\models\PaymentReality;
use common\models\PaymentCommitment;
use common\forms\ActionForm;
use yii\helpers\ArrayHelper;

class ApprovePaymentCommitmentForm extends ActionForm
{
    public $id;
    public $payment_reality_id;
    public $note;
    public $variance = 0.2;
    public $confirmed_by;

    protected $_commitment;
    protected $_reality;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'payment_reality_id'], 'trim'],
            [['id', 'payment_reality_id'], 'required'],
            ['id', 'validateCommitment'],
            ['payment_reality_id', 'validateReality'],
            ['variance', 'validateVariance'],
            ['note', 'trim'],
            ['confirmed_by', 'trim']
        ];
    }

    public function validateCommitment($attribute)
    {
        $commitment = $this->getCommitment();
        if (!$commitment) {
            return $this->addError($attribute, 'Giao dịch không tồn tại');
        }
        if (!$commitment->isPending()) {
            return $this->addError($attribute, 'Giao dịch này không thể được xác nhận');
        }
    }

    public function validateReality($attribute)
    {
        $reality = $this->getReality();
        if (!$reality) {
            return $this->addError($attribute, 'Mã nhận tiền không tồn tại');
        }

        if (!$reality->isPending()) {
            return $this->addError($attribute, 'Mã nhận tiền này không thể sử dụng');
        }
    }

    public function validateVariance($attribute)
    {
        if ($this->hasErrors()) return;
        if (!$this->variance) return;
        $reality = $this->getReality();
        $commitment = $this->getCommitment();
        $variance = (float)($reality->kingcoin - $commitment->kingcoin);
        if ( $variance < (-1) * $this->variance) {
            return $this->addError($attribute, sprintf('Chênh lệch giữa giao dịch và mã nhân tiền quá lớn (> %s)', $this->variance));
        }
    }


    public function getCommitment()
    {
        if (!$this->_commitment) {
            $this->_commitment = PaymentCommitment::find()->where(['id' => $this->id])->one();
        }
        return $this->_commitment;
    }

    public function setCommitment($commitment)
    {
        $this->_commitment = $commitment;
    }

    public function getReality()
    {
        if (!$this->_reality) {
            $this->_reality = PaymentReality::findOne($this->payment_reality_id);
        }
        return $this->_reality;
    }

    public function setReality($reality)
    {
        $this->_reality = $reality;
    }

    public function approve()
    {
        if (!$this->validate()) return false;

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $now = date('Y-m-d H:i:s');
        try {
            $commitment = $this->getCommitment();
            $commitment->payment_reality_id = $this->payment_reality_id;
            $commitment->note = $this->note;
            $commitment->confirmed_at = $now;
            $commitment->confirmed_by = $this->confirmed_by;
            $commitment->status = PaymentCommitment::STATUS_APPROVED;
            $commitment->save();

            $reality = $this->getReality();
            $object = $commitment->object;
            $reality->payment_commitment_id = $commitment->id;
            $reality->status = PaymentReality::STATUS_CLAIMED;
            $reality->object_name = $commitment->object_name;
            $reality->object_key = $commitment->object_key;
            $reality->object_created_at = $object->created_at;
            $reality->confirmed_at = $now;
            $reality->confirmed_by = $this->confirmed_by;
            $reality->user_id = $commitment->user_id;
            $reality->save();

            // Update object
            if ($commitment->object_name == PaymentCommitment::OBJECT_NAME_ORDER) {
                if ($commitment->bulk) {
                    $childs = PaymentCommitment::find()->where(['parent' => $commitment->id])->all();
                    foreach ($childs as $child) {
                        $movePendingForm = new UpdateOrderStatusPendingForm(['id' => $child->object_key]);
                        if (!$movePendingForm->save()) {
                            throw new \Exception($movePendingForm->getFirstErrorMessage());
                        }    
                    }
                } else {
                    $movePendingForm = new UpdateOrderStatusPendingForm(['id' => $commitment->object_key]);
                    if (!$movePendingForm->save()) {
                        throw new \Exception($movePendingForm->getFirstErrorMessage());
                    }
                }
                
            } elseif ($commitment->object_name == PaymentCommitment::OBJECT_NAME_WALLET) {
                $movePendingForm = new CompletePaymentTransactionForm(['id' => $commitment->object_key]);
                if (!$movePendingForm->save()) {
                    throw new \Exception($movePendingForm->getFirstErrorMessage());
                }
            }
            $transaction->commit();
            return true;
        } catch(\Exception $e) {
            $transaction->rollback();
            $this->addError('payment_reality_id', $e->getMessage());
            return false;
        }
    }

    public function fetchPendingReality()
    {
        $realities = PaymentReality::find()->where(['status' => PaymentReality::STATUS_PENDING])->all();
        return ArrayHelper::map($realities, 'id', function($record) {
            return $record->getId();
        });
    }
}
