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

    const OBJECT_NAME_CUSTOMER = 'customer';
    const OBJECT_NAME_ADMIN = 'admin';
    const OBJECT_NAME_SUPPLIER = 'supplier';

    public function init()
    {
        parent::init();
        // Hook message queue to EVENT_AFTER_UPDATE
        $this->on(self::EVENT_AFTER_INSERT, function ($event) {
            Yii::$app->queue->push(new \common\queue\NotifyOrderMessageJob([
                'orderId' => $event->sender->order_id,
                'messageId' => $event->sender->id
            ]));
        });
    }
    
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
        return $this->object_name == self::OBJECT_NAME_CUSTOMER;
    }

    public function isAdmin()
    {
        return $this->object_name == self::OBJECT_NAME_ADMIN;
    }

    public function isSupplier()
    {
        return $this->object_name == self::OBJECT_NAME_SUPPLIER;
    }
}
