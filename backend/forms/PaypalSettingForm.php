<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class PaypalSettingForm extends Model
{
    public $client_id;
    public $client_secret;
    public $sandbox_client_id;
    public $sandbox_client_secret;
    public $currency = 'USD';
    public $mode;
    public $fee;
    public $status;

    public $username;
    public $password;

    public function rules()
    {
        return [
            [['client_id', 'client_secret', 'currency', 'sandbox_client_id', 'sandbox_client_secret', 'fee'], 'trim'],
            ['status', 'boolean'],
            ['mode', 'in', 'range' => ['sandbox', 'live']],
            [['username', 'password'], 'required'],
        ];
    }
}