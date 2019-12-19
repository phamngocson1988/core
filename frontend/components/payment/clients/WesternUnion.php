<?php
namespace frontend\components\payment\clients;

use Yii;

class WesternUnion extends OfflinePayment
{
	public $currency = 'USD';
    public function loadConfig()
    {
        $settings = Yii::$app->settings;
    	return [
            "content" => $settings->get('WesternUnionSettingForm', 'content'),
            "logo" => $settings->get('WesternUnionSettingForm', 'logo'),
            "logo_width" => $settings->get('WesternUnionSettingForm', 'logo_width'),
    		"logo_height" => $settings->get('WesternUnionSettingForm', 'logo_height'),
    	];
    }
}