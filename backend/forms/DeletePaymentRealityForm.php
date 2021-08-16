<?php

namespace backend\forms;

use Yii;
use common\models\PaymentReality;

class DeletePaymentRealityForm extends ActionForm
{
    public $id;
    public $deleted_note;
    protected $_payment;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validatePayment'],
            ['deleted_note', 'trim'],
            ['deleted_note', 'required']
        ];
    }

    public function validatePayment($attribute)
    {
        $payment = $this->getPayment();
        if (!$payment) {
            return $this->addError($attribute, 'Giao dịch không tồn tại');
        }
        if ($payment->isClaimed()) {
            return $this->addError($attribute, 'Giao dịch này đã được duyệt nên không thể bị xoá');
        }

        if ($payment->isDeleted()) {
            return $this->addError($attribute, 'Giao dịch này đã bị xoá trước đó');
        }
    }

    public function getPayment()
    {
        if (!$this->_payment) {
            $this->_payment = PaymentReality::findOne($this->id);
        }
        return $this->_payment;
    }

    public function delete()
    {
        if (!$this->validate()) return false;
        $payment = $this->getPayment();
        $payment->status = PaymentReality::STATUS_DELETED;
        $payment->deleted_note = $this->deleted_note;
        $payment->deleted_by = Yii::$app->user->id;
        $payment->deleted_at = date('Y-m-d h:i:s');
        return $payment->save();
    }


}
