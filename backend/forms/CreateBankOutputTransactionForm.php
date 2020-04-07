<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\BankTransaction;
use backend\models\BankAccount;

class CreateBankOutputTransactionForm extends CreateTransactionForm
{
    public $amount;
    public $bank_account_id;
    public $description;
    public $fee;
    public $apply_fee;
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

            ['fee', 'number', 'min' => 0, 'message' => 'Phí chuyển khoản không hợp lệ'],

            ['bank_account_id', 'required', 'message' => 'Bạn hãy chọn tài khoản nhận tiền'],
            ['description', 'trim'],

            ['apply_fee', 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => 'Loại giao dịch',
            'transaction_type' => 'Ngân hàng / Tiền mặt',
            'amount' => 'Số tiền',
            'fee' => 'Phí chuyển khoản',
            'bank_account_id' => 'Tài khoản gửi tiền',
            'description' => 'Mô tả',
            'status' => 'Trạng thái giao dịch',
            'bank_account_id' => 'Tài khoản ngân hàng gửi tiền',
            'apply_fee' => 'Gộp chi phí và số tiền thành 1 giao dịch'
        ];

    }

    public function create()
    {
        $bankAccount = $this->getBankAccount();
        $bank = $bankAccount->bank;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            $thread = new BankTransaction();
            $thread->type = BankTransaction::TYPE_OUT;        
            $thread->transaction_type = BankTransaction::TRANSACTION_TYPE_BANK;
            $thread->bank_id = $bank->id;
            $thread->currency = $bank->currency;
            $thread->country = $bank->country;
            $thread->bank_account_id = $this->bank_account_id;
            $thread->description = $this->description;
            $thread->amount = (-1) * $this->amount;
            $thread->status = $this->status;

            if ($this->apply_fee) {
                $thread->amount = (-1) * ($this->amount + $this->fee);
                $thread->description = sprintf("%s\nGộp phí %s", $thread->description, number_format($this->fee));
            }

            if ($thread->isCompleted()) {
                $thread->completed_at = date('Y-m-d H:i:s');
                $thread->completed_by = Yii::$app->user->id;
            }

            $thread->save(); // Save transaction

            if (!$this->apply_fee) {
                $feeTransfer = new BankTransaction();
                $feeTransfer->type = BankTransaction::TYPE_OUT;        
                $feeTransfer->transaction_type = BankTransaction::TRANSACTION_TYPE_BANK;
                $feeTransfer->bank_id = $bank->id;
                $feeTransfer->currency = $bank->currency;
                $feeTransfer->country = $bank->country;
                $feeTransfer->bank_account_id = $this->bank_account_id;
                $feeTransfer->description = sprintf("Phí chuyển khoản cho giao dịch %s", $thread->id);
                $feeTransfer->amount = (-1) * $this->fee;     
                $feeTransfer->status = $this->status;

                if ($feeTransfer->isCompleted()) {
                    $feeTransfer->completed_at = date('Y-m-d H:i:s');
                    $feeTransfer->completed_by = Yii::$app->user->id;
                
                }
                $feeTransfer->save();   
            }

            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', $e->getMessage());
            return false;
        }
    }

    public function fetchTransactionStatusList()
    {
        return [
            BankTransaction::STATUS_PENDING => 'Giao dịch tạm',
            BankTransaction::STATUS_COMPLETED => 'Giao dịch hoàn tất',
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
