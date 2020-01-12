<?php
namespace supplier\models;

use Yii;

class SupplierBank extends \common\models\SupplierBank
{
	const SCENARIO_CREATE = 'SCENARIO_CREATE';

	public function scenarios()
    {
        $parents = parent::scenarios();
        return [
            self::SCENARIO_CREATE => ['supplier_id', 'bank_code', 'province', 'city', 'branch', 'account_number', 'account_name'],
        ];
    }

    public function rules()
    {
        return [
            [['supplier_id', 'bank_code', 'account_number', 'account_name'], 'required',],
            [['province', 'city', 'branch'], 'safe'],
        ];
    }
}