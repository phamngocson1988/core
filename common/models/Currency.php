<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Currency extends Model
{
    public $name;
    public $symbol;
    public $format;
    
    public static function fetchAll() 
    {
        $models = ArrayHelper::getValue(Yii::$app->params, 'currency', []);
        return array_map(function($arr) {
            return new Currency($arr);
        }, $models);
    }

    public static function findOne($code)
    {
        $models = ArrayHelper::getValue(Yii::$app->params, 'currency', []);
        $model = ArrayHelper::getValue($models, $code, null);
        return new Currency($model);
    }

    public function addSymbolFormat($number)
    {
        return sprintf($this->format, $number);
    }

    public public function convertUSDToCurrency($number, $currency)
    {
        if ($currency == 'USD') return $number;
        $key = sprintf('exchange_rate_%s', strtolower($currency));
        return Yii::$app->settings->get('ApplicationSettingForm', $key);
    }
}