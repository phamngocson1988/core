<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Order;

/**
 * OrderItems model
 */
class OrderItems extends ActiveRecord
{
    const TYPE_PRODUCT = 'product';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_items}}';
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
