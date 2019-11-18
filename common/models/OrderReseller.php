<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class OrderReseller extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%order_reseller}}';
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

    public static function primaryKey()
    {
        return ["order_id"];
    } 

    public function getOrder() 
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}