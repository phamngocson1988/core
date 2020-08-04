<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class UserNotificationSetting extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_notification_setting}}';
    }
}