<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\ThreadTransaction;
use backend\models\BankAccount;

class CreateTransactionForm extends Model
{
    public $type;
    public $transaction_type;
    public $amount;
    public $bank_account_id;
    public $description;
    public $status;

    protected $_bank_account;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $typeList = $this->fetchTypeList();
        $transactionTypeList = $this->fetchTransactionTypeList();
        $statusList = $this->fetchTransactionStatusList();
        return [
            ['type', 'required', 'message' => 'Bạn cần xác định loại giao dịch nạp tiền hoặc chuyển tiền'],
            ['type', 'in', 'range' => array_keys($typeList), 'message' => 'Loại giao dịch không hợp lệ'],

            ['transaction_type', 'required', 'message' => 'Bạn cần xác định loại giao dịch ngân hàng hoặc tiền mặt'],
            ['transaction_type', 'in', 'range' => array_keys($transactionTypeList), 'message' => 'Loại giao dịch không hợp lệ'],

            ['status', 'required', 'message' => 'Bạn cần xác định trạng thái của giao dịch này'],
            ['status', 'in', 'range' => array_keys($statusList), 'message' => 'Trạng thái giao dịch không hợp lệ'],

            ['amount', 'required', 'message' => 'Bạn cần nhập số tiền'],
            ['amount', 'number', 'min' => 0, 'message' => 'Số tiền không hợp lệ'],

            ['bank_account_id', 'required', 'message' => 'Bạn hãy chọn tài khoản ngân hàng'],
            ['description', 'trim'],
        ];
    }

    

    public function attributeLabels()
    {
        $labels = [
            'type' => 'Loại giao dịch',
            'transaction_type' => 'Ngân hàng / Tiền mặt',
            'amount' => 'Số tiền',
            'bank_account_id' => 'Tài khoản ngân hàng',
            'description' => 'Mô tả',
            'status' => 'Trạng thái giao dịch',
        ];
        if ($this->type == ThreadTransaction::TYPE_IN) {
            $labels['bank_account_id'] = 'Tài khoản ngân hàng nhận';
        } else {
            $labels['bank_account_id'] = 'Tài khoản ngân hàng gửi';
        }

        return $labels;
    }

    public function create()
    {
        $bankAccount = $this->getBankAccount();
        $bank = $bankAccount->bank;

        $thread = new ThreadTransaction();
        $thread->type = $this->type;        
        $thread->transaction_type = $this->transaction_type;
        $thread->bank_id = $bank->id;
        $thread->currency = $bank->currency;
        $thread->country = $bank->country;
        $thread->amount = $this->amount;
        $thread->bank_account_id = $this->bank_account_id;
        $thread->description = $this->description;
        $thread->status = $this->status;
        
        return $thread->save() ? $thread : null;
    }

    public function fetchTypeList()
    {
        return [
            ThreadTransaction::TYPE_IN => 'Nạp tiền',
            ThreadTransaction::TYPE_OUT => 'Chuyển tiền',
        ];
    }

    public function fetchTransactionTypeList()
    {
        return [
            ThreadTransaction::TRANSACTION_TYPE_BANK => 'Giao dịch ngân hàng',
            ThreadTransaction::TRANSACTION_TYPE_CASH => 'Giao dịch tiền mặt',
        ];
    }

    public function fetchTransactionStatusList()
    {
        return [
            ThreadTransaction::STATUS_PENDING => 'Giao dịch tạm',
            ThreadTransaction::STATUS_COMPLETED => 'Giao dịch hoàn tất',
        ];
    }

    public function getBankAccount()
    {
        if (!$this->_bank_account) {
            $this->_bank_account = BankAccount::findOne($this->bank_account_id);
        }
        return $this->_bank_account;
    }
}
