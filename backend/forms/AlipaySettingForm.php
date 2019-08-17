<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class AlipaySettingForm extends Model
{
    public $partner;
    public $seller_email;
    public $key;

    public $bank_name;
    public $account_number;
    public $account_holder;
    public $nickname;
    public $region;
    public $logo;

    public function rules()
    {
        return [
            [['partner', 'seller_email', 'key'], 'trim'],
            [['bank_name', 'account_number', 'account_holder', 'nickname', 'region', 'logo'], 'trim'],
        ];
    }
}