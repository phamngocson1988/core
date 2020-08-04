<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Auth extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%auth}}';
    }

    public function getUser() 
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}