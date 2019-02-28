<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Order;

/**
 * OrderComments model
 */
class OrderComments extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_comments}}';
    }
}
