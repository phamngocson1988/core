<?php
namespace backend\models;

use Yii;

class Supplier extends \common\models\Supplier
{
	const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    public $units = [];
    
    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['user_id', 'password'],
            self::SCENARIO_EDIT => ['user_id'],
        ];
    }

    public function rules()
    {
        return [
            ['id', 'required'],
            ['password', 'trim'],
        ];
    }
}