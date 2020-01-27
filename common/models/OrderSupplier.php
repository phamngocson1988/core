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

    public function canBeTaken()
    {
        $order = $this->order;
        $orderStatus = [Order::STATUS_VERIFYING, Order::STATUS_PENDING, Order::STATUS_PROCESSING];
        $requestStatus = [self::STATUS_REQUEST, self::STATUS_APPROVE];
        if (in_array($order->status, $orderStatus) && in_array($this->status, $requestStatus)) return true;
        return false;
    }
}
