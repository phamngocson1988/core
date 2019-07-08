<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Game model
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $image_id
 */
class PromotionUser extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%promotion_user}}';
    }

    public static function primaryKey()
    {
        return ["promotion_id", "user_id"];
    }
}