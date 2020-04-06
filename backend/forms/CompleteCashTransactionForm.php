<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\CashTransaction;

class CompleteCashTransactionForm extends Model
{
    public $id;

    protected $_bank_transaction;

    public function rules()
    {
        return [
            ['id', 'required', 'message' => 'Không tìm thấy giao dịch'],
            ['id', 'validateTransaction']
        ];
    }

    public function validateTransaction($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $transaction = $this->getCashTransaction();
        if (!$transaction) {
            $this->addError($attribute, 'Không tìm thấy giao dịch');
        }
    }

    public function complete()
    {
        $transaction = $this->getCashTransaction();
        if ($transaction->isCompleted()) return true;
        $transaction->status = CashTransaction::STATUS_COMPLETED;
        return $transaction->save();
    }

    public function getCashTransaction()
    {
        if (!$this->_bank_transaction) {
            $this->_bank_transaction = CashTransaction::findOne($this->id);
        }
        return $this->_bank_transaction;
    }
}
