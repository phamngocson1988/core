<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Order;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
/**
 * OrderComplains model
 */
class OrderComplains extends ActiveRecord
{
    const IS_CUSTOMER = 'Y';
    const IS_NOT_CUSTOMER = 'N';

    public static function tableName()
    {
        return '{{%order_complains}}';
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
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
        ];
    }

    public function getSender()
    {
    	return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getSenderName()
    {
    	return $this->sender->name;
    }

    public function isCustomer()
    {
        return $this->is_customer == self::IS_CUSTOMER;
    }
}
