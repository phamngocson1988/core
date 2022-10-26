<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\WhitelistIp;

class WhitelistSettingForm extends Model
{
    public $status;
    public function rules()
    {

        return [
            ['status', 'default', 'value' => 0],
        ];
    }

    public function fetch() 
    {
        return WhitelistIp::find()->where(['status' => 0])->all();
    }
}