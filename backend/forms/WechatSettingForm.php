<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class WechatSettingForm extends Model
{
    public $bank_name;
    public $account_number;
    public $account_holder;
    public $region;
    public $content;
    public $logo;

    public function rules()
    {
        return [
            [['bank_name', 'account_number', 'account_holder', 'region', 'content', 'logo'], 'trim'],
        ];
    }
}