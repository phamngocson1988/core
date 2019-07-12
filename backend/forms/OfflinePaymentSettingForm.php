<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class OfflinePaymentSettingForm extends Model
{
    public $bank_name1;
    public $account_number1;
    public $account_holder1;

    public $bank_name2;
    public $account_number2;
    public $account_holder2;

    public $bank_name3;
    public $account_number3;
    public $account_holder3;

    public $bank_name4;
    public $account_number4;
    public $account_holder4;

    public function rules()
    {
        return [
            [['bank_name1', 'account_number1', 'account_holder1'], 'trim'],
            [['bank_name2', 'account_number2', 'account_holder2'], 'trim'],
            [['bank_name3', 'account_number3', 'account_holder3'], 'trim'],
            [['bank_name4', 'account_number4', 'account_holder4'], 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bank_name1' => 'Tên ngân hàng',
            'account_number1' => 'Số tài khoản',
            'account_holder1' => 'Tên tài khoản',

            'bank_name2' => 'Tên ngân hàng',
            'account_number2' => 'Số tài khoản',
            'account_holder2' => 'Tên tài khoản',

            'bank_name3' => 'Tên ngân hàng',
            'account_number3' => 'Số tài khoản',
            'account_holder3' => 'Tên tài khoản',

            'bank_name4' => 'Tên ngân hàng',
            'account_number4' => 'Số tài khoản',
            'account_holder4' => 'Tên tài khoản',
        ];
    }
}