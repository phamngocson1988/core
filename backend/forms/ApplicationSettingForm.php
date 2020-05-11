<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class ApplicationSettingForm extends Model
{
    public $logo;

    public function rules()
    {
        return [
            ['logo', 'trim']
        ];
    }

    public function attributeLabels()
    {
        return [
            'logo' => 'Logo',
        ];
    }
}