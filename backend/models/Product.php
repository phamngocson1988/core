<?php
namespace backend\models;

use Yii;

class Product extends \common\models\Product
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'game_id', 'price', 'unit'],
            self::SCENARIO_EDIT => ['id', 'title', 'game_id', 'price', 'unit'],
        ];
    }

    public function rules()
    {
        return [
            ['id', 'required', 'on' => self::SCENARIO_EDIT],
            [['title', 'game_id', 'price', 'unit'], 'required'],
            ['status', 'default', 'value' => self::STATUS_VISIBLE],
        ];
    }
}
