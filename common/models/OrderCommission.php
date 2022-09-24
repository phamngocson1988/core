<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * OrderCommission model
 */
class OrderCommission extends ActiveRecord
{
    const COMMSSION_TYPE_ORDER = 'order';
    const COMMSSION_TYPE_SELLOUT = 'sellout';

    const USER_ROLE_SALER = 'saler';
    const USER_ROLE_ORDERTEAM = 'orderteam';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_commission}}';
    }

    public static function primaryKey()
    {
        return ['order_id', 'user_id', 'commission_type', 'role'];
    }
}