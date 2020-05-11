<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Country
 */
class Country extends Model
{
    public $country_code;
    public $country_name;
    public $dialling_code;
    
    public static function fetchAll() 
    {
        $countries = Yii::$app->params['country'];
        return array_map(function($arr) {
            return new Country($arr);
        }, $countries);
    }

    public static function findOne($code)
    {
        $countries = self::fetchAll();
        $map = array_column($countries, null, 'country_code');
        return ArrayHelper::getValue($map, $code, null);
    }
}