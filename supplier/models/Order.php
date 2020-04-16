<?php
namespace supplier\models;

use Yii;
use supplier\behaviors\OrderComplainBehavior;

/**
 * Order model
 */
class Order extends \common\models\Order
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['complain'] = OrderComplainBehavior::className();
        return $behaviors;
    }
    
    public function getStatusLabel($format = '<span class="label label-%s">%s</span>')
    {
        $list = [
            self::STATUS_VERIFYING => 'default',
            self::STATUS_PENDING => 'info',
            self::STATUS_PROCESSING => 'primary',
            self::STATUS_PARTIAL => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_DELETED => 'default',
            self::STATUS_CANCELLED => 'default',
            self::STATUS_CONFIRMED => 'default',
        ];
        $labels = self::getStatusList();
        $color = $list[$this->status];
        $label = $labels[$this->status];
        if (!$format) return $label;
        return sprintf($format, $color, $label);
    }
}