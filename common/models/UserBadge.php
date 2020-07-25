<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class UserBadge extends ActiveRecord
{
    const BADGE_PROFILE = 'profile';
    const BADGE_COMPLAIN = 'complain';
    const BADGE_REVIEW = 'review';
    
    public static function tableName()
    {
        return '{{%user_badge}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }
}