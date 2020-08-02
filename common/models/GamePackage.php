<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class GamePackage extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%game_package}}';
    }
}