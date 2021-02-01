<?php

namespace backend\forms;

use Yii;
use common\models\PaymentCommitment;

class DeletePaymentCommitmentForm extends ActionForm
{
    public $id;

    protected $_commitment;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateCommitment']
        ];
    }

    public function validateCommitment($attribute)
    {
        $commitment = $this->getCommitment();
        if (!$commitment) {
            return $this->addError($attribute, 'Giao dịch không tồn tại');
        }
        if ($commitment->isApproved()) {
            return $this->addError($attribute, 'Giao dịch này đã được duyệt nên không thể bị xoá');
        }
    }


    public function getCommitment()
    {
        if (!$this->_commitment) {
            $this->_commitment = PaymentCommitment::findOne($this->id);
        }
        return $this->_commitment;
    }

    public function delete()
    {
        if (!$this->validate()) return false;
        $commitment = $this->getCommitment();
        return $commitment->delete();
    }

}
