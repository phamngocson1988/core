<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * OrderComments model
 */
class OrderSupplier extends ActiveRecord
{
	const STATUS_REQUEST = 'request';
	const STATUS_APPROVE = 'approve';
	const STATUS_REJECT = 'reject';
	const STATUS_RETAKE = 'retake';
	const STATUS_STOP = 'stop';

    public static function tableName()
    {
        return '{{%order_supplier}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public function getOrder() 
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'supplier_id']);
    }

    public function isRequest()
    {
        return $this->status == self::STATUS_REQUEST;
    }

    public function isApprove()
    {
        return $this->status == self::STATUS_APPROVE;
    }
}
