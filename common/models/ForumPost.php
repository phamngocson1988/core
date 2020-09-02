<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\behaviors\ForumLikeBehavior;

class ForumPost extends ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 10;

    const APPROVED_YES = true;
    const APPROVED_NO = false;

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

    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'active'),
            self::STATUS_INACTIVE => Yii::t('app', 'inactive'),
            // self::STATUS_DELETED => Yii::t('app', 'deleted'),
        ];
    }

    public function getStatusLabel()
    {
        $labels = self::getStatusList();
        return ArrayHelper::getValue($labels, $this->status, '');
    }

    public static function getApproveStatus()
    {
        return [
            self::APPROVED_YES => Yii::t('app', 'yes'),
            self::APPROVED_NO => Yii::t('app', 'no'),
        ];
    }
}