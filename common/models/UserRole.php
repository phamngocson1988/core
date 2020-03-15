<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class UserRole extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%user_role}}';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
