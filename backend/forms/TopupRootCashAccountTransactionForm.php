<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\CashTransaction;
use backend\models\CashAccount;

class TopupRootCashAccountTransactionForm extends CreateTransactionForm
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
            ['bank_account_id', 'validateRootAccount'],

            ['description', 'trim'],
            ['description', 'required', 'message' => 'Bạn hãy nhập mô tả giao dịch']
        ];
    }

    public function validateRootAccount($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $account = $this->getBankAccount();
        if (!$account->isRoot()) {
            $this->addError($attribute, 'Tài khoản này không hợp lệ');
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
        $bank = $bankAccount->bank;

        $thread = new CashTransaction();
        $thread->type = CashTransaction::TYPE_IN;        
        $thread->transaction_type = CashTransaction::TRANSACTION_TYPE_CASH;
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
            CashTransaction::STATUS_PENDING => 'Giao dịch tạm',
            CashTransaction::STATUS_COMPLETED => 'Giao dịch hoàn tất',
        ];
    }

    public function getBankAccount()
    {
        if (!$this->_bank_account) {
            $this->_bank_account = CashAccount::find()
            ->andWhere(['id' => $this->bank_account_id])
            ->andWhere(['root' => CashAccount::ROOT_ACCOUNT])
            ->one();
        }
        return $this->_bank_account;
    }
}
