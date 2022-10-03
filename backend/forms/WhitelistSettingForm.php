<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class WhitelistSettingForm extends Model
{
    public $status;
    public $whitelist;
    public $unwhitelist;
    public function rules()
    {

        return [
            ['status', 'default', 'value' => 0],
            [['whitelist', 'unwhitelist'], 'safe'],
        ];
    }
}