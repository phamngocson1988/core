<?php
namespace frontend\components\payment\clients;

use Yii;

class Wechat extends OfflinePayment
{
	public $currency = 'CNY';
    public function loadConfig()
    {
        $settings = Yii::$app->settings;
    	return [
            "content" => $settings->get('WechatSettingForm', 'content'),
    		"logo" => $settings->get('WechatSettingForm', 'logo'),
    	];
    }
}