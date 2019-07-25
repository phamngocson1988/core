<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class AffiliateProgramForm extends Model
{
    public $value;
    public $type;
    public $content;
    public $status;
    public $duration; // days

    public function rules()
    {
        return [
            [['content'], 'trim'],
            ['status', 'default', 'value' => 0],
            ['type', 'in', 'range' => ['fix', 'percent']],
            ['type', 'default', 'value' => 'fix'],
            [['value', 'duration'], 'number'],
            ['duration', 'default', 'value' => 30],
        ];
    }
}