<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\BankTransaction;

class DeleteBankTransactionForm extends Model
{
    public $id;

    protected $_bank_transaction;

    public function rules()
    {
        return [
            ['id', 'required', 'message' => 'Không tìm thấy giao dịch'],
            ['id', 'validateTransaction'],
        ];
    }

    public function validateTransaction($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $transaction = $this->getBankTransaction();
        if (!$transaction) {
            $this->addError($attribute, 'Giao dịch không tồn tại');
            return;
        };
        if ($transaction->isCompleted()) {
            $this->addError($attribute, 'Giao dịch này đã hoàn tất, bạn không thể xóa giao dịch này');
            return;
        }
    }

    public function delete()
    {
        $transaction = $this->getBankTransaction();
        return $transaction->delete();
    }

    public function getBankTransaction()
    {
        if (!$this->_bank_transaction) {
            $this->_bank_transaction = BankTransaction::findOne($this->id);
        }
        return $this->_bank_transaction;
    }
}
