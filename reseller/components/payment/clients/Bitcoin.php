<?php
namespace reseller\components\payment\clients;

use Yii;

class Bitcoin extends OfflinePayment
{
	public $currency = 'USD';
    public function loadConfig()
    {
        $settings = Yii::$app->settings;
    	return [
            "content" => $settings->get('BitcoinSettingForm', 'content'),
            "logo" => $settings->get('BitcoinSettingForm', 'logo'),
            "logo_width" => $settings->get('BitcoinSettingForm', 'logo_width'),
    		"logo_height" => $settings->get('BitcoinSettingForm', 'logo_height'),
    	];
    }
}