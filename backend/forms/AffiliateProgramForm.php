<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class AffiliateProgramForm extends Model
{
    public $value;
    public $reseller_value; // The value applied for reseller in case the type is "FIX"
    public $type;
    public $content;
    public $min_member;
    public $status;
    public $duration; // days

    public function rules()
    {
        return [
            [['content'], 'trim'],
            ['status', 'default', 'value' => 0],
            ['type', 'in', 'range' => ['fix', 'percent']],
            ['type', 'default', 'value' => 'fix'],
            [['value', 'duration', 'reseller_value'], 'number'],
            ['duration', 'default', 'value' => 30],
            ['min_member', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => 'Terms and conditions'
        ];
    }
}