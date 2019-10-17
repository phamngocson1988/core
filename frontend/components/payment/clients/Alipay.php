<?php
namespace frontend\components\payment\clients;

use Yii;

class Alipay extends OfflinePayment
{
	public $currency = 'CNY';
	
    public function loadConfig()
    {
        $settings = Yii::$app->settings;
    	return [
    		"Payment method" => $settings->get('AlipaySettingForm', 'bank_name'),
    		"Alipay account's number" => $settings->get('AlipaySettingForm', 'account_number'),
    		"Alipay account's name" => $settings->get('AlipaySettingForm', 'account_holder'),
    		"Nick name" => $settings->get('AlipaySettingForm', 'nickname'),
            "Region" => $settings->get('AlipaySettingForm', 'region'),
    		"content" => $settings->get('AlipaySettingForm', 'content'),
    		"logo" => $settings->get('AlipaySettingForm', 'logo'),
    	];
    }
}