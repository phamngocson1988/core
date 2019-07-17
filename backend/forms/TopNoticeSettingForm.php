<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class TopNoticeSettingForm extends Model
{
    public $top_notice;

    public function rules()
    {
        return [
            [['top_notice'], 'safe'],
        ];
    }
}