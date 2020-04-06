<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\BankTransaction;

class CompleteBankTransactionForm extends Model
{
    public $id;

    protected $_bank_transaction;

    public function rules()
    {
        return [
            ['id', 'required', 'message' => 'Bạn phải nhập mã giao dịch'],
            ['id', 'validateTransaction']
        ];
    }

    public function validateTransaction($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $transaction = $this->getBankTransaction();
        if (!$transaction) {
            $this->addError($attribute, 'Không tìm thấy giao dịch');
        }
    }

    public function complete()
    {
        $transaction = $this->getBankTransaction();
        if ($transaction->isCompleted()) return true;
        $transaction->status = BankTransaction::STATUS_COMPLETED;
        return $transaction->save();
    }

    public function getBankTransaction()
    {
        if (!$this->_bank_transaction) {
            $this->_bank_transaction = BankTransaction::findOne($this->id);
        }
        return $this->_bank_transaction;
    }
}
