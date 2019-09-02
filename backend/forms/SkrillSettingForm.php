<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class SkrillSettingForm extends Model
{
    public $pay_to_email;
    public $secret_word;
    public $logo;

    public function rules()
    {
        return [
            [['pay_to_email', 'secret_word', 'logo'], 'trim'],
        ];
    }
}