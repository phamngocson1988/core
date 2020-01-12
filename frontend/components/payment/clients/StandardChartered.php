<?php
namespace frontend\components\payment\clients;

use Yii;

class StandardChartered extends OfflinePayment
{
	public $currency = 'USD';
    public function loadConfig()
    {
        $settings = Yii::$app->settings;
    	return [
            "content" => $settings->get('StandardCharteredSettingForm', 'content'),
            "logo" => $settings->get('StandardCharteredSettingForm', 'logo'),
            "logo_width" => $settings->get('StandardCharteredSettingForm', 'logo_width'),
    		"logo_height" => $settings->get('StandardCharteredSettingForm', 'logo_height'),
    	];
    }
}