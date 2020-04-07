<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\CashTransaction;
use backend\models\CashAccount;

class TopupCashAccountTransactionForm extends CreateTransactionForm
{
    public $amount;
    public $bank_account_id;
    public $description;
    public $status;

    protected $_bank_account;
    protected $_root_account;
    protected $_root_amount;
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
            ['amount', 'validateAmount'],

            ['bank_account_id', 'required', 'message' => 'Bạn hãy chọn tài khoản nhận tiền'],
            ['bank_account_id', 'validateAccount'],

            ['description', 'trim'],
            ['description', 'required', 'message' => 'Bạn hãy nhập mô tả giao dịch']
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
        if ($this->amount > $this->getRootAmount()) {
            $this->addError($attribute, 'Không thể chuyển nhiều hơn số tiền quỹ hiện có');
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
            // topup account
            $thread1 = new CashTransaction();
            $thread1->type = CashTransaction::TYPE_IN;        
            $thread1->transaction_type = CashTransaction::TRANSACTION_TYPE_CASH;
            $thread1->bank_id = $bank->id;
            $thread1->currency = $bank->currency;
            $thread1->country = $bank->country;
            $thread1->amount = $this->amount;
            $thread1->bank_account_id = $bankAccount->id;
            $thread1->description = sprintf("Nhận được từ quỹ tiền mặt với mô tả: %s", $this->description);
            $thread1->status = $this->status;
            if ($thread1->isCompleted()) {
                $thread1->completed_at = date('Y-m-d H:i:s');
                $thread1->completed_by = Yii::$app->user->id;
            }
            $thread1->save();

            // withdraw from root
            $thread2 = new CashTransaction();
            $thread2->type = CashTransaction::TYPE_OUT;        
            $thread2->transaction_type = CashTransaction::TRANSACTION_TYPE_CASH;
            $thread2->bank_id = $bank->id;
            $thread2->currency = $bank->currency;
            $thread2->country = $bank->country;
            $thread2->amount = (-1) * $this->amount;
            $thread2->bank_account_id = $rootAccount->id;
            $thread2->description = $this->description;
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

    public function getRootAmount()
    {
        if (!$this->_root_amount) {
            // Remaining amount, including pending and completed
            $root = $this->getRootAccount();
            $command = CashTransaction::find()
            ->where(['bank_account_id' => $root->id]);
            $this->_root_amount = $command->sum('amount');
        }
        return $this->_root_amount;
    }
}
