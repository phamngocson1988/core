<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * PromotionGame
 */
class PromotionGame extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%promotion_game}}';
    }

    public static function primaryKey()
    {
        return ["promotion_id", "game_id"];
    }
}