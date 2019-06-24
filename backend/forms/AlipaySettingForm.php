<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class AlipaySettingForm extends Model
{
    public $partner;
    public $seller_email;
    public $key;

    public function rules()
    {
        return [
            [['partner', 'seller_email', 'key'], 'trim'],
        ];
    }
}