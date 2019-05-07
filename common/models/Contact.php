<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Contact extends ActiveRecord
{
	const SCENARIO_CREATE = 'create';
	const SCENARIO_EDIT = 'edit';

    public static function tableName()
    {
        return '{{%contact}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['user_id', 'phone', 'name', 'description'];
        $scenarios[self::SCENARIO_EDIT] = ['id', 'phone', 'name', 'description'];
        return $scenarios;
    }

    public function rules()
    {
        return [
        	['id', 'required', 'on' => self::SCENARIO_EDIT],
        	['user_id', 'required', 'on' => self::SCENARIO_CREATE],
            [['phone', 'name'], 'required'],
            ['description', 'trim']
        ];
    }
}
