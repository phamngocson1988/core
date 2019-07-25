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
            [['content', 'duration'], 'trim'],
            ['status', 'default', 'value' => 0],
            ['type', 'in', 'range' => ['fix', 'percent']],
            ['type', 'default', 'value' => 'fix'],
            [['value', 'duration'], 'integer'],
            ['duration', 'default', 'value' => 30],
        ];
    }
}