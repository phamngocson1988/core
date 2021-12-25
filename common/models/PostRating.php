<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * PostRating model
 *
 * @property integer $post_id
 * @property integer $user_id
 * @property integer $rating
 */
class PostRating extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%post_rating}}';
    }

    public static function primaryKey()
    {
        return ["post_id", "user_id"];
    }
}