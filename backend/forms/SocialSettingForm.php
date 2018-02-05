<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class SocialSettingForm extends Model
{
    public $facebook;
    public $twitter;
    public $gplus;
    public $rss;

    public function rules()
    {
        return [
            [['facebook', 'twitter', 'gplus', 'rss'], 'trim'],
        ];
    }

}