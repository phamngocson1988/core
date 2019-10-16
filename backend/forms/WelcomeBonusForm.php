<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class WelcomeBonusForm extends Model
{
    public $content;
    public $value; // King coin
    public $status;
    public $topup_value;

    public function rules()
    {
        return [
            [['content', 'value', 'topup_value'], 'trim'],
            [['status'], 'default', 'value' => 0],
        ];
    }
}