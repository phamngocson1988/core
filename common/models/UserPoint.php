<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

class UserPoint extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_point}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public static function definedLevelPoint()
    {
        return [
            0 => 0,
            1 => 500,
            2 => 2500,
            3 => 5000,
            4 => 10000,
            5 => 15000,
            6 => 25000,
        ];
    }

    public static function getLevelByPoint($point)
    {
        $levels = self::definedLevelPoint();
        $keys = array_reverse($levels);
        $level = current(array_filter($keys, function($key) use ($point) {
            return $point >= $key;
        }));
        return $level;
    }

    public static function getPointByLevel($level)
    {
        $levels = self::definedLevelPoint();
        return ArrayHelper::getValue($levels, $level, 0);
    }
}