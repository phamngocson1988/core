<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class GameSetting extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%game_setting}}';
    }
}