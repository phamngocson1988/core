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
            "logo_width" => $settings->get('WechatSettingForm', 'logo_width'),
    		"logo_height" => $settings->get('WechatSettingForm', 'logo_height'),
    	];
    }
}