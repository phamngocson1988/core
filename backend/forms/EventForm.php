<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class EventForm extends Model
{
    public $image;
    public $status;

    public function rules()
    {

        return [
            [['image'], 'required'],
            ['status', 'default', 'value' => 0],
        ];
    }
}