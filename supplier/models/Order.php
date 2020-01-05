<?php
namespace supplier\models;

use Yii;
use supplier\behaviors\OrderSupplierBehavior;

/**
 * Order model
 */
class Order extends \common\models\Order
{
    // Process order
    const SCENARIO_GO_PROCESSING = 'go_processing';
    const SCENARIO_GO_COMPLETED = 'go_completed';
    const SCENARIO_ASSIGN_SUPPLIER = 'SCENARIO_ASSIGN_SUPPLIER';

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
            self::SCENARIO_CREATE => ['game_id', 'customer_id', 'quantity', 'username', 'password', 'platform', 'login_method', 'character_name', 'recover_code', 'server', 'note'],
            self::SCENARIO_VERIFYING => ['game_id', 'customer_id', 'total_unit', 'username', 'password', 'platform', 'login_method', 'character_name', 'recover_code', 'server', 'note'],
            self::SCENARIO_PENDING => ['username', 'password', 'platform', 'login_method', 'character_name', 'recover_code', 'server', 'note'],
            self::SCENARIO_GO_PROCESSING => [],
            self::SCENARIO_GO_COMPLETED => [],
        ]);
    }

    public function rules()
    {
        return [
            [['game_id', 'customer_id', 'quantity'], 'required', 'on' => self::SCENARIO_CREATE],
            [['game_id', 'customer_id', 'total_unit'], 'required', 'on' => self::SCENARIO_VERIFYING],
            [['username', 'password', 'platform', 'login_method', 'character_name'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_VERIFYING]],
            [['recover_code', 'server', 'note'], 'trim', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_VERIFYING]],
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