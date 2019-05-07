<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class ImportSettingForm extends Model
{
    public $import_contact_template;

    public function rules()
    {
        return [
            ['import_contact_template', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'import_contact_template' => 'Mẫu import danh bạ',
        ];
    }
}