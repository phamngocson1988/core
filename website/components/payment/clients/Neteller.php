<?php
namespace website\components\payment\clients;

use Yii;

class Neteller extends OfflinePayment
{
	public $currency = 'USD';
    public function loadConfig()
    {
        $settings = Yii::$app->settings;
    	return [
            "content" => $settings->get('NetellerSettingForm', 'content'),
            "logo" => $settings->get('NetellerSettingForm', 'logo'),
            "logo_width" => $settings->get('NetellerSettingForm', 'logo_width'),
    		"logo_height" => $settings->get('NetellerSettingForm', 'logo_height'),
    	];
    }
}