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
    const STATUS_TEMP = 'temp';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DONE = 'done';
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

    // public function rules()
    // {
    //     return [
    //         [['customer_id', 'customer_name', 'customer_email', 'customer_phone', 'status'], 'required'],
    //         ['status', 'default', 'value' => self::STATUS_TEMP],
    //     ];
    // }

    public static function getStatusList()
    {
        return [
            self::STATUS_TEMP => 'Temporary',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_DONE => 'Done',
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
}
