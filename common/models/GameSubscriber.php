<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class GameSubscriber extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%game_subscriber}}';
    }
}