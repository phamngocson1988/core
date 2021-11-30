<?php
namespace payment\forms;

use Yii;

class WalletPaymentForm extends \website\forms\WalletPaymentForm
{
    public $remark;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['remark', 'trim'];
        $rules[] = ['remark', 'required', 'message' => 'Name is required'];
        return $rules;
    }
}