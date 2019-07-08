<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class ImportSettingForm extends Model
{
    public $import_reseller_template;

    public function rules()
    {
        return [
            ['import_reseller_template', 'trim'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'import_reseller_template' => 'Mẫu import dành cho người bán hàng',
        ];
    }
}