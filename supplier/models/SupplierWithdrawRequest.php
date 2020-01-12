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
            self::SCENARIO_CREATE => ['supplier_id', 'bank_id', 'amount'],
            self::SCENARIO_CANCEL => ['supplier_id', 'cancelled_at', 'cancelled_by', 'status'],
        ];
    }

    public function rules()
    {
        return [
            [['supplier_id', 'bank_id', 'amount'], 'required', 'on' => self::SCENARIO_CREATE],
            [['supplier_id', 'cancelled_at', 'cancelled_by', 'status'], 'required', 'on' => self::SCENARIO_CANCEL],
        ];
    }
}
