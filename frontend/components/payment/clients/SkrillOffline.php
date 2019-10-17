<?php
namespace frontend\components\payment\clients;

use Yii;

class SkrillOffline extends OfflinePayment
{
	public $currency = 'USD';
    public function loadConfig()
    {
        $settings = Yii::$app->settings;
    	return [
    		"Skrill account" => $settings->get('SkrillSettingForm', 'pay_to_email'),
            "content" => $settings->get('SkrillSettingForm', 'content'),
    		"logo" => $settings->get('SkrillSettingForm', 'logo'),
    	];
    }
}