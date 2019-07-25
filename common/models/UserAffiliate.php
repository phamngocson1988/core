<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class UserAffiliate extends ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_COMPLETED = 1;

	public static function tableName()
    {
        return '{{%user_affiliate}}';
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