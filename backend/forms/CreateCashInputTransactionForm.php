<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\ThreadTransaction;
use backend\models\BankAccount;

class CreateCashInputTransactionForm extends CreateTransactionForm
{
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
            ['status', 'required', 'message' => 'Bạn cần xác định trạng thái của giao dịch này'],
            ['status', 'in', 'range' => array_keys($statusList), 'message' => 'Trạng thái giao dịch không hợp lệ'],

            ['amount', 'required', 'message' => 'Bạn cần nhập số tiền'],
            ['amount', 'number', 'min' => 0, 'message' => 'Số tiền không hợp lệ'],

            ['bank_account_id', 'required', 'message' => 'Bạn hãy chọn tài khoản nhận tiền'],
            ['description', 'trim'],
        ];
    }

    

    public function attributeLabels()
    {
        return [
            'type' => 'Loại giao dịch',
            'transaction_type' => 'Ngân hàng / Tiền mặt',
            'amount' => 'Số tiền',
            'bank_account_id' => 'Tài khoản ngân hàng',
            'description' => 'Mô tả',
            'status' => 'Trạng thái giao dịch',
            'bank_account_id' => 'Tài khoản ngân hàng nhận tiền'
        ];

    }

    public function create()
    {
        $bankAccount = $this->getBankAccount();
        $bank = $bankAccount->bank;

        $thread = new ThreadTransaction();
        $thread->type = ThreadTransaction::TYPE_IN;        
        $thread->transaction_type = ThreadTransaction::TRANSACTION_TYPE_BANK;
        $thread->bank_id = $bank->id;
        $thread->currency = $bank->currency;
        $thread->country = $bank->country;
        $thread->amount = $this->amount;
        $thread->bank_account_id = $this->bank_account_id;
        $thread->description = $this->description;
        $thread->status = $this->status;
        
        return $thread->save() ? $thread : null;
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
