<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Order;

/**
 * OrderComplains model
 */
class OrderComplains extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_complains}}';
    }
}
