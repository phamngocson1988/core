<?php
namespace supplier\models;

use Yii;
use supplier\behaviors\OrderSupplierBehavior;

/**
 * Order model
 */
class Order extends \common\models\Order
{
    const SCENARIO_ACCEPT = 'SCENARIO_ACCEPT';
    const SCENARIO_REJECT = 'SCENARIO_REJECT';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => OrderSupplierBehavior::className()
        ];
        return $behaviors;
    }

    public function scenarios()
    {
        $parents = parent::scenarios();
        return array_merge($parents, [
            self::SCENARIO_ACCEPT => ['supplier_accept'],
            self::SCENARIO_REJECT => ['supplier_accept'],
        ]);
    }

    public function rules()
    {
        return [
            ['supplier_accept', 'required'],
        ];
    }

    public function getStatusLabel($format = '<span class="label label-%s">%s</span>')
    {
        $list = [
            self::STATUS_VERIFYING => 'default',
            self::STATUS_PENDING => 'info',
            self::STATUS_PROCESSING => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_DELETED => 'default',
            self::STATUS_CANCELLED => 'default',
            self::STATUS_CONFIRMED => 'default',
        ];
        $labels = self::getStatusList();
        $color = $list[$this->status];
        $label = $labels[$this->status];
        return sprintf($format, $color, $label);
    }
}