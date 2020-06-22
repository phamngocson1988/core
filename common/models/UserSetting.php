<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class UserSetting extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_setting}}';
    }
}