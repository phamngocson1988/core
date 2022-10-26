<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\WhitelistIp;

class RequestAccessForm extends Model
{
    public $ip;
    public $name;

    public function rules()
    {
        return [
            [['ip', 'name'], 'trim'],
            [['ip', 'name'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $request = WhitelistIp::findOne($this->ip);
        if (!$request) {
          $request = new WhitelistIp();
          $request->ip = $this->ip;
        }
        $request->name = $this->name;
        return $request->save();
    }
}

