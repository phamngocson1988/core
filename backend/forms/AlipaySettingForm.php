<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class AlipaySettingForm extends Model
{
    public $partner;
    public $seller_email;
    public $key;

    public $content;
    public $logo;
    public $logo_width;
    public $logo_height;

    public function rules()
    {
        return [
            [['partner', 'seller_email', 'key'], 'trim'],
            [['content', 'logo'], 'trim'],
            [['logo_width', 'logo_height'], 'number']
        ];
    }
}