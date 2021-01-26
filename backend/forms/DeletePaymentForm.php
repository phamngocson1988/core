<?php

namespace backend\forms;

use Yii;
use common\models\Payment;

class DeletePaymentForm extends ActionForm
{
    public $id;

    protected $_payment;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validatePayment']
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
    }

    public function getPayment()
    {
        if (!$this->_payment) {
            $this->_payment = Payment::findOne($this->id);
        }
        return $this->_payment;
    }

    public function delete()
    {
        $payment = $this->getPayment();
        return $payment->delete();
    }

}
