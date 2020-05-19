<?php
namespace website\models;

use Yii;
use website\behaviors\OrderComplainBehavior;
use website\behaviors\OrderNotificationBehavior;
/**
 * Order model
 */
class Order extends \common\models\Order
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['complain'] = OrderComplainBehavior::className();
        $behaviors['notification'] = OrderNotificationBehavior::className();
        return $behaviors;
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_VERIFYING => 'Verifying',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_PARTIAL => 'Processing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_DELETED => 'Deleted',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public function getStatusLabel($format = '')
    {
        $list = [
            self::STATUS_VERIFYING => 'text-gray-1',
            self::STATUS_PENDING => 'text-primary',
            self::STATUS_PROCESSING => 'text-secondary',
            self::STATUS_COMPLETED => 'text-secondary-3',
            self::STATUS_PARTIAL => 'text-secondary-3',
            self::STATUS_CONFIRMED => 'text-secondary-3',
            self::STATUS_CANCELLED => 'text-gray-1',
            self::STATUS_DELETED => 'text-gray-1'
        ];
        $labels = self::getStatusList();
        $color = $list[$this->status];
        $label = $labels[$this->status];
        if (!$format) return $label;
        return sprintf($format, $color, $label);
    }
}