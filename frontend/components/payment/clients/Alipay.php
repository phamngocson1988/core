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
    		"content" => $settings->get('AlipaySettingForm', 'content'),
    		"logo" => $settings->get('AlipaySettingForm', 'logo'),
    		"logo_width" => $settings->get('AlipaySettingForm', 'logo_width'),
    		"logo_height" => $settings->get('AlipaySettingForm', 'logo_height'),
    	];
    }
}