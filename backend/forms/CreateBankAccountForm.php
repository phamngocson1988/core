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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['account_name', 'trim'],
            ['account_name', 'required', 'message' => 'Bạn hãy nhập tên tài khoản'],

            ['account_number', 'trim'],
            ['account_number', 'required', 'message' => 'Bạn hãy nhập số tài khoản'],

            [['branch', 'branch_address'], 'trim'],

            ['bank_id', 'required', 'message' => 'Bạn hãy chọn ngân hàng tương ứng'],
        ];
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
        $bank = new BankAccount();
        $bank->account_name = $this->account_name;        
        $bank->account_number = $this->account_number;
        $bank->branch = $this->branch;
        $bank->branch_address = $this->branch_address;
        $bank->bank_id = $this->bank_id;
        
        return $bank->save() ? $bank : null;
    }

    public function fetchBank()
    {
        $banks = Bank::find()->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }
}
