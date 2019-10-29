<?php
namespace frontend\components\payment\clients;

use Yii;

class Payoneer extends OfflinePayment
{
	public $currency = 'USD';
    public function loadConfig()
    {
        $settings = Yii::$app->settings;
    	return [
            "content" => $settings->get('PayoneerSettingForm', 'content'),
            "logo" => $settings->get('PayoneerSettingForm', 'logo'),
            "logo_width" => $settings->get('PayoneerSettingForm', 'logo_width'),
    		"logo_height" => $settings->get('PayoneerSettingForm', 'logo_height'),
    	];
    }
}