<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class PostalSavingsBankOfChinaSettingForm extends Model
{
    public $content;
    public $logo;
    public $logo_width;
    public $logo_height;

    public function rules()
    {
        return [
            [['content', 'logo'], 'trim'],
            [['logo_width', 'logo_height'], 'number']
        ];
    }
}