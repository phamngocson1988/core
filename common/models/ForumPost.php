<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\behaviors\ForumLikeBehavior;

class ForumPost extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'like' => ForumLikeBehavior::className(),
        ];
    }

    public static function tableName()
    {
        return '{{%forum_post}}';
    }

    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getTopic()
    {
        return $this->hasOne(ForumTopic::className(), ['id' => 'topic_id']);
    }
}