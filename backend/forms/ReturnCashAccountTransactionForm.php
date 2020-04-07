<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\CashTransaction;
use backend\models\CashAccount;

class ReturnCashAccountTransactionForm extends CreateTransactionForm
{
    public $amount;
    public $bank_account_id;
    public $status;

    protected $_bank_account;
    protected $_account_amount;
    protected $_root_account;
    
    public function init() 
    {
        if ($this->amount === null) {
            $this->amount = $this->getAccountAmount();
        }
    }
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
            ['amount', 'validateAmount'],

            ['bank_account_id', 'required', 'message' => 'Bạn hãy chọn tài khoản nhận tiền'],
            ['bank_account_id', 'validateAccount'],

            ['description', 'trim'],
        ];
    }

    public function validateAccount($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $root = $this->getRootAccount();
        if (!$root) {
            $this->addError($attribute, 'Không tìm thấy quỹ tiền mặt tương ứng');
            return;
        }
        $account = $this->getBankAccount();
        if (!$account) {
            $this->addError($attribute, 'Tài khoản không tồn tại');
        }
    }

    public function validateAmount($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        if ($this->amount > $this->getAccountAmount()) {
            $this->addError($attribute, 'Không thể chuyển trả về quỹ nhiều hơn số tiền nhân viên đang giữ.');
        }
    }

    public function attributeLabels()
    {
        return [
            'amount' => 'Số tiền',
            'description' => 'Mô tả',
        ];

    }

    public function create()
    {
        $bankAccount = $this->getBankAccount();
        $rootAccount = $this->getRootAccount();
        $bank = $bankAccount->bank;

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            // withdraw account
            $thread1 = new CashTransaction();
            $thread1->type = CashTransaction::TYPE_OUT;        
            $thread1->transaction_type = CashTransaction::TRANSACTION_TYPE_CASH;
            $thread1->bank_id = $bank->id;
            $thread1->currency = $bank->currency;
            $thread1->country = $bank->country;
            $thread1->amount = (-1) * $this->amount;
            $thread1->bank_account_id = $bankAccount->id;
            $thread1->description = $this->description ? $this->description : 'Hoàn trả về quỹ';
            $thread1->status = $this->status;
            if ($thread1->isCompleted()) {
                $thread1->completed_at = date('Y-m-d H:i:s');
                $thread1->completed_by = Yii::$app->user->id;
            }
            $thread1->save();

            // topup from root
            $thread2 = new CashTransaction();
            $thread2->type = CashTransaction::TYPE_IN;        
            $thread2->transaction_type = CashTransaction::TRANSACTION_TYPE_CASH;
            $thread2->bank_id = $bank->id;
            $thread2->currency = $bank->currency;
            $thread2->country = $bank->country;
            $thread2->amount = $this->amount;
            $thread2->bank_account_id = $rootAccount->id;
            $thread2->description = sprintf("Được hoàn trả từ tài khoản %s", $bankAccount->account_name);
            $thread2->status = $this->status;
            if ($thread2->isCompleted()) {
                $thread2->completed_at = date('Y-m-d H:i:s');
                $thread2->completed_by = Yii::$app->user->id;
            }
            $thread2->save();

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('name', $e->getMessage());
            return false;
        }
    }

    public function fetchTransactionStatusList()
    {
        return [
            CashTransaction::STATUS_PENDING => 'Giao dịch tạm',
            CashTransaction::STATUS_COMPLETED => 'Giao dịch hoàn tất',
        ];
    }

    public function getBankAccount()
    {
        if (!$this->_bank_account) {
            $this->_bank_account = CashAccount::find()
            ->andWhere(['id' => $this->bank_account_id])
            ->one();
        }
        return $this->_bank_account;
    }

    public function getRootAccount()
    {
        if (!$this->_root_account) {
            $account = $this->getBankAccount();
            $this->_root_account = CashAccount::find()
            ->andWhere(['bank_id' => $account->bank_id])
            ->andWhere(['root' => CashAccount::ROOT_ACCOUNT])
            ->one();
        }
        return $this->_root_account;
    }

    public function getAccountAmount()
    {
        if (!$this->_account_amount) {
            // Remaining amount
            $account = $this->getBankAccount();
            $command = CashTransaction::find()
            ->where(['bank_account_id' => $account->id])
            ->andWhere(['status' => CashTransaction::STATUS_COMPLETED]);
            $this->_account_amount = $command->sum('amount');
        }
        return $this->_account_amount;
    }

}
