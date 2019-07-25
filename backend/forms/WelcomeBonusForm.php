<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class WelcomeBonusForm extends Model
{
    public $content;
    public $value; // King coin
    public $status;

    public function rules()
    {
        return [
            [['content'], 'trim'],
            [['value'], 'trim'],
            [['status'], 'default', 'value' => 0],
        ];
    }
}