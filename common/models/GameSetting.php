<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class GameSetting extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%game_setting}}';
    }

    public static function fetchMethod()
    {
    	$setting = self::find()->where(['key' => 'method'])->one();
        $settingValues = explode(",", $setting->value);

        $mapping = [];
        foreach ($settingValues as $settingValue) {
	        $methodParts = explode("|", $settingValue);
            $methodTitle = array_shift($methodParts);
            $slugTitle = Inflector::slug($methodTitle);
            $mapping[$slugTitle] = $methodTitle;
        }
        return $mapping;
    }

    public static function fetchVersion()
    {
    	$setting = self::find()->where(['key' => 'method'])->one();
        return self::buildMapping($setting->value);
    }

    public static function fetchPackage()
    {
    	$setting = self::find()->where(['key' => 'package'])->one();
        return self::buildMapping($setting->value);
    }

    public static function buildMapping($string)
    {
    	$settingValues = explode(",", $string);
        $keys = array_map(function($val) {
            return Inflector::slug($val);
        }, $settingValues);
        return array_combine($keys, $settingValues);
    }
}