<?php
namespace backend\models;

use Yii;

/**
 * Order model
 */
class Order extends \common\models\Order
{
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $new = [
            // self::SCENARIO_VERIFYING => ['game_id', 'quantity', 'username', 'password', 'platform', 'login_method', 'character_name', 'recover_code', 'server', 'note'],
            self::SCENARIO_PENDING => ['username', 'password', 'platform', 'login_method', 'character_name', 'recover_code', 'server', 'note'],
        ];
        return array_merge($scenarios, $new);
    }

    public function rules()
    {
        return [
            // [['game_id', 'product_id', 'quantity'], 'required', 'on' => self::SCENARIO_VERIFYING],
            // ['game_id', 'validateGame', 'on' => self::SCENARIO_VERIFYING],
            // ['product_id', 'validateProduct', 'on' => self::SCENARIO_VERIFYING],
            [['username', 'password', 'platform', 'login_method', 'character_name'], 'required'],
            [['recover_code', 'server', 'note'], 'trim'],
        ];
    }

    public function getStatusLabel($format = '<span class="label label-%s">%s</span>')
    {
        $list = [
            self::STATUS_VERIFYING => 'default',
            self::STATUS_PENDING => 'primary',
            self::STATUS_PROCESSING => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_DELETED => 'danger'
        ];
        $labels = self::getStatusList();
        $color = $list[$this->status];
        $label = $labels[$this->status];
        return sprintf($format, $color, $label);
    }
}