<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * PostLike model
 *
 * @property integer $post_id
 * @property integer $user_id
 */
class PostLike extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%post_like}}';
    }

    public static function primaryKey()
    {
        return ["post_id", "user_id"];
    }
}