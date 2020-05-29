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
            "logo_width" => $settings->get('SkrillSettingForm', 'logo_width'),
    		"logo_height" => $settings->get('SkrillSettingForm', 'logo_height'),
    	];
    }

    public function getFee($total)
    {
        $settings = Yii::$app->settings;
        $fee = $settings->get('SkrillSettingForm', 'fee', 0);
        return number_format($fee * $total / 100, 1);
    }
}