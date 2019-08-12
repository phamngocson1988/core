<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class ReferProgramForm extends Model
{
    public $min_total_price;
    public $gift_value;
    public $status;

    public function rules()
    {

        return [
            [['gift_value', 'min_total_price'], 'number'],
            ['status', 'default', 'value' => 0],
        ];
    }
}