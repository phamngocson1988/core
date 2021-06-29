<?php
namespace supplier\models;

use Yii;

class SupplierGame extends \common\models\SupplierGame
{
	const SCENARIO_CREATE = 'add';
	const SCENARIO_STATUS = 'status';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return array_merge($scenarios, [
            self::SCENARIO_CREATE => ['supplier_id', 'game_id'],
            self::SCENARIO_STATUS => ['supplier_id', 'game_id', 'status'],
        ]);
    }
	public function rules()
    {
        return [
            [['supplier_id', 'game_id'], 'required'],
            ['status', 'required', 'on' => self::SCENARIO_STATUS],
        ];
    }
}