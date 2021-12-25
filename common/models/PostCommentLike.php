<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * PostLike model
 *
 * @property integer $comment_id
 * @property integer $user_id
 */
class PostCommentLike extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%post_comment_like}}';
    }

    public static function primaryKey()
    {
        return ["comment_id", "user_id"];
    }
}