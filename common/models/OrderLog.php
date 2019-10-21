<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class OrderLog extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%order_log}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }
    
    public function getUser() 
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getOrder() 
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}