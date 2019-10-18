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
            "content" => $settings->get('SkrillSettingForm', 'content'),
    		"logo" => $settings->get('SkrillSettingForm', 'logo'),
    	];
    }
}