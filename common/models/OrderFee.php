<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Order;

/**
 * OrderFee model
 */
class OrderFee extends ActiveRecord
{
    const TYPE_DISCOUNT = 'discount';
    const TYPE_PROMOTION = 'promotion';
    const TYPE_FEE = 'fee';
    const TYPE_TAX = 'tax';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_fee}}';
    }
}
