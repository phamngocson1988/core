<?php
namespace backend\forms;

use Yii;
use backend\models\BankAccount;
use backend\models\Bank;
use yii\helpers\ArrayHelper;

class EditBankAccountForm extends BankAccount
{
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

    public function fetchBank()
    {
        $banks = Bank::find()->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }
}
