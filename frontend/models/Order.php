<?php
namespace frontend\models;

use Yii;

/**
 * Order model
 */
class Order extends \common\models\Order
{
    const SCENARIO_CANCELORDER = 'cancel_order';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $new = [
            self::SCENARIO_CANCELORDER => ['request_cancel', 'request_cancel_time'],
        ];
        return array_merge($scenarios, $new);
    }

    public function rules()
    {
        return [
            [['request_cancel', 'request_cancel_time'], 'required', 'on' => self::SCENARIO_CANCELORDER],
        ];
    }

    public function getStatusLabel($format = '<span class="%s">%s</span>')
    {
        $list = [
            self::STATUS_VERIFYING => 'text-gray-1',
            self::STATUS_PENDING => 'text-primary',
            self::STATUS_PROCESSING => 'text-secondary',
            self::STATUS_COMPLETED => 'text-secondary-3',
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