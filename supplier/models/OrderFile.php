<?php
namespace supplier\models;

use Yii;

class OrderFile extends \common\models\OrderFile
{
    const SCENARIO_CREATE = 'create';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['order_id', 'file_id', 'file_type'];
        return $scenarios;
    }

    public function rules()
    {
        return [
        	[['order_id', 'file_id', 'file_type'], 'required', 'on' => self::SCENARIO_CREATE],
        ];
    }
}