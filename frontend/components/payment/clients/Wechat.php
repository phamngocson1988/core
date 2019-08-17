<?php
namespace frontend\components\payment\clients;

use Yii;

class Wechat extends OfflinePayment
{
    public function loadConfig()
    {
        $settings = Yii::$app->settings;
    	return [
    		"Payment method" => $settings->get('WechatSettingForm', 'bank_name'),
    		"Alipay account's number" => $settings->get('WechatSettingForm', 'account_number'),
    		"Alipay account's name" => $settings->get('WechatSettingForm', 'account_holder'),
    		"Region" => $settings->get('WechatSettingForm', 'region'),
    		"logo" => $settings->get('WechatSettingForm', 'logo'),
    	];
    }
}