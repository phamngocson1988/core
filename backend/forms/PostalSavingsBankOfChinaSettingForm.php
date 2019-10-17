<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class PostalSavingsBankOfChinaSettingForm extends Model
{
    public $content;
    public $logo;

    public function rules()
    {
        return [
            [['content', 'logo'], 'trim'],
        ];
    }
}