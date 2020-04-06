<?php
namespace common\components\helpers;

use Yii;
use yii\helpers\ArrayHelper;

class CommonHelper
{
	public static function fetchCountry()
	{
		return ArrayHelper::getValue(Yii::$app->params, 'country');
	}

	public static function getCountry($code)
	{
		$country = self::fetchCountry();
		return ArrayHelper::getValue($country, $code, '');
	}

	public static function fetchCurrency()
	{
		return ArrayHelper::getValue(Yii::$app->params, 'currency');
	}
}