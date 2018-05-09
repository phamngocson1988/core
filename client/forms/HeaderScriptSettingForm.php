<?php

namespace client\forms;

use Yii;
use yii\base\Model;

class HeaderScriptSettingForm extends Model
{
    public $code;

    public function rules()
    {
        return [
            ['code', 'trim'],
        ];
    }
}