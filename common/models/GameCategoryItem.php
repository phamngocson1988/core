<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class GameCategoryItem extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%game_category_item}}';
    }
}