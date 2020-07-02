<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class GameGroup extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%game_group}}';
    }
}