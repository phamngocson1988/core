<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\BankAccount;
use backend\models\Bank;

class CreateBankAccountForm extends Model
{
    public $account_name;
    public $account_number;
    public $branch;
    public $branch_address;
    public $bank_id;

    protected $_bank;

    public function rules()
    {
        return [
            ['account_name', 'trim'],
            ['account_name', 'required', 'message' => 'Bạn hãy nhập tên tài khoản'],

            ['account_number', 'trim'],
            ['account_number', 'required', 'message' => 'Bạn hãy nhập số tài khoản'],

            [['branch', 'branch_address'], 'trim'],

            ['bank_id', 'required', 'message' => 'Bạn hãy chọn ngân hàng tương ứng'],
            ['bank_id', 'validateBank'],
        ];
    }

    public function validateBank($attribute, $params) 
    {
        $bank = $this->getBank();
        if (!$bank) {
            $this->addError($attribute, 'Bạn hãy chọn ngân hàng tương ứng');
        }
    }

    public function attributeLabels()
    {
        return [
            'account_name' => 'Tên tài khoản',
            'account_number' => 'Số tài khoản',
            'branch' => 'Chi nhánh',
            'branch_address' => 'Địa chỉ chi nhánh',
            'bank_id' => 'Ngân hàng',
        ];
    }

    public function create()
    {
        $bank = $this->getBank();
        $account = new BankAccount();
        $account->account_name = $this->account_name;        
        $account->account_number = $this->account_number;
        $account->branch = $this->branch;
        $account->branch_address = $this->branch_address;
        $account->bank_id = $this->bank_id;
        $account->bank_type = $bank->bank_type;
        
        return $account->save() ? $account : null;
    }

    public function fetchBank()
    {
        $banks = Bank::find()->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }

    public function getBank()
    {
        if (!$this->_bank) {
            $this->_bank = Bank::findOne($this->bank_id);
        }
        return $this->_bank;
    }
}
