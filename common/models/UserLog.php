<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class UserLog extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_log}}';
    }

    public static function primaryKey()
    {
        return ['user_id'];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'last_activity',
                'updatedAtAttribute' => 'last_activity',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}