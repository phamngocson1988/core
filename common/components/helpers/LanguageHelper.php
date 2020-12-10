<?php

namespace common\components\helpers;

use Yii;
use yii\helpers\ArrayHelper;

class LanguageHelper 
{
    public static function getLanguageName($key)
    {
        $data = ArrayHelper::getValue(Yii::$app->params['languages'], $key, []);
        return ArrayHelper::getValue($data, 'title', '');
    }

    public static function fetchLanguages()
    {
        return ArrayHelper::map(Yii::$app->params['languages'], 'code', 'title');
    }
}
?>
