<?php
namespace backend\forms;

use Yii;
use backend\models\PaymentTransaction;
use common\events\PaymentTransactionEvent;

class CompletePaymentTransactionForm extends ActionForm
{
    public $id;
    
    private $_payment;

    public function rules()
    {
        return [
            [['id'], 'required'],
            ['id', 'validatePayment'],
        ];
    }

    public function validatePayment($attribute, $params)
    {
        $payment = $this->getPayment();
        if (!$payment) {
            $this->addError($attribute, 'Đơn hàng mua Kingcoin không tồn tại');
        } elseif (!$payment->isPending()) {
            $this->addError($attribute, 'Không thể chuyển trạng thái đơn hàng Kingcoin này');
        }
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $payment = $this->getPayment();
        $payment->on(PaymentTransaction::EVENT_AFTER_UPDATE, [PaymentTransactionEvent::className(), 'welcomeBonus']);
        $payment->on(PaymentTransaction::EVENT_AFTER_UPDATE, [PaymentTransactionEvent::className(), 'topupUserWallet']);
        $payment->on(PaymentTransaction::EVENT_AFTER_UPDATE, [PaymentTransactionEvent::className(), 'applyReferGift']);
        $payment->status = PaymentTransaction::STATUS_COMPLETED;
        $result = $payment->save();
        $payment->runJobHandlers();
        return $result;
    }

    public function getPayment()
    {
        if ($this->_payment === null) {
            $this->_payment = PaymentTransaction::findOne($this->id);
        }
        return $this->_payment;
    }
}
