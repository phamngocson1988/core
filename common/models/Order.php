<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\Customer;
use common\models\OrderItems;
use common\models\OrderComments;
use common\models\OrderComplains;
use yii\behaviors\TimestampBehavior;

/**
 * Order model
 */
class Order extends ActiveRecord
{
    const STATUS_VERIFYING = 'verifying';
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DELETED = 'deleted';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
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

    public static function getStatusList()
    {
        return [
            self::STATUS_VERIFYING => 'Verifying',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_DELETED => 'Deleted'
        ];
    }

    public function getSaler()
    {
        return $this->hasOne(User::className(), ['id' => 'saler_id']);
    }

    public function getMarketing()
    {
        return $this->hasOne(User::className(), ['id' => 'marketing_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    public function getItems() 
    {
        return $this->hasMany(OrderItems::className(), ['order_id' => 'id']);
    }

    public function getComments() 
    {
        return $this->hasMany(OrderComments::className(), ['order_id' => 'id']);
    }

    public function getComplains() 
    {
        return $this->hasMany(OrderComplains::className(), ['order_id' => 'id']);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(10);
    }

    public function isTempOrder()
    {
        return $this->status === self::STATUS_VERIFYING;
    }

    /**
     * check if the order is in temporary status or not. The system only allow to delete temporary order
     * If not, just move it to 'deleted' status.
     * Before deleting the order, delete all its order items
     */
    public function beforeDelete()
    {
        if (!$this->isTempOrder()) return false;
        if (parent::beforeDelete()) {
            $items = $this->items;
            foreach ($this->items as $item) {
                $item->delete();
            }
        }
    }
}
