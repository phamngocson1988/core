<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class FlashAnnouncementForm extends Model
{
    public $content;
    public $status;

    public function rules()
    {
        return [
            [['content'], 'trim'],
            [['status'], 'default', 'value' => 0],
        ];
    }
}