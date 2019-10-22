<?php
namespace frontend\components\payment\clients;

use Yii;

class PostalSavingsBankOfChina extends OfflinePayment
{
	public $currency = 'CNY';
	
    public function loadConfig()
    {
        $settings = Yii::$app->settings;
    	return [
    		"content" => $settings->get('PostalSavingsBankOfChinaSettingForm', 'content'),
			"logo" => $settings->get('PostalSavingsBankOfChinaSettingForm', 'logo'),
			"logo_width" => $settings->get('PostalSavingsBankOfChinaSettingForm', 'logo_width'),
    		"logo_height" => $settings->get('PostalSavingsBankOfChinaSettingForm', 'logo_height'),
    	];
    }
}