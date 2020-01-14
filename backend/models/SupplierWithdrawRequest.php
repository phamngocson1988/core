<?php
namespace backend\models;

use Yii;

class SupplierWithdrawRequest extends \common\models\SupplierWithdrawRequest
{
    const SCENARIO_CANCEL = 'SCENARIO_CANCEL';
    const SCENARIO_APPROVE = 'SCENARIO_APPROVE';
    const SCENARIO_DONE = 'SCENARIO_DONE';
    const SCENARIO_EVIDENCE = 'SCENARIO_EVIDENCE';

    public function scenarios()
    {
        $parents = parent::scenarios();
        return [
            self::SCENARIO_CANCEL => ['cancelled_at', 'cancelled_by', 'status'],
            self::SCENARIO_APPROVE => ['approved_at', 'approved_by', 'status'],
            self::SCENARIO_DONE => ['done_at', 'done_by', 'status'],
            self::SCENARIO_EVIDENCE => ['evidence'],
        ];
    }

    public function rules()
    {
        return [
            [['cancelled_at', 'cancelled_by', 'status'], 'required', 'on' => self::SCENARIO_CANCEL],
            [['approved_at', 'approved_by', 'status'], 'required', 'on' => self::SCENARIO_APPROVE],
            [['done_at', 'done_by', 'status'], 'required', 'on' => self::SCENARIO_DONE],
            [['evidence'], 'safe', 'on' => self::SCENARIO_EVIDENCE],
        ];
    }

	public function getStatusLabel($format = '<span class="label label-%s">%s</span>')
    {
        $list = [
            self::STATUS_REQUEST => 'warning',
            self::STATUS_APPROVE => 'info',
            self::STATUS_DONE => 'primary',
            self::STATUS_CANCEL => 'default',
        ];
        $labels = self::getStatusList();
        $color = $list[$this->status];
        $label = $labels[$this->status];
        return sprintf($format, $color, $label);
    }
}
