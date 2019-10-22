<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class SkrillSettingForm extends Model
{
    public $pay_to_email;
    public $secret_word;
    public $content;
    public $logo;
    public $logo_width;
    public $logo_height;

    public function rules()
    {
        return [
            [['pay_to_email', 'secret_word', 'content', 'logo'], 'trim'],
            [['logo_width', 'logo_height'], 'number']
        ];
    }
}