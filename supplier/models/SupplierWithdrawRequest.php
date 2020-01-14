<?php
namespace supplier\models;

use Yii;

class SupplierWithdrawRequest extends \common\models\SupplierWithdrawRequest
{
	const SCENARIO_CREATE = 'SCENARIO_CREATE';
	const SCENARIO_CANCEL = 'SCENARIO_CANCEL';

	public function scenarios()
    {
        $parents = parent::scenarios();
        return [
            self::SCENARIO_CREATE => ['supplier_id', 'bank_code', 'account_name', 'account_number', 'amount'],
            self::SCENARIO_CANCEL => ['supplier_id', 'cancelled_at', 'cancelled_by', 'status'],
        ];
    }

    public function rules()
    {
        return [
            [['supplier_id', 'bank_code', 'account_name', 'account_number', 'amount'], 'required', 'on' => self::SCENARIO_CREATE],
            [['supplier_id', 'cancelled_at', 'cancelled_by', 'status'], 'required', 'on' => self::SCENARIO_CANCEL],
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
