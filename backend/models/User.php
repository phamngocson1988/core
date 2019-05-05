<?php
namespace backend\models;

use common\models\User as CommonUser;

class User extends CommonUser
{
	const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['name', 'country_code', 'phone', 'address', 'birthday', 'status'],
            self::SCENARIO_EDIT => ['id', 'name', 'country_code', 'phone', 'address', 'birthday', 'status'],
        ];
    }

    public function rules()
    {
        return [
            ['id', 'required', 'on' => self::SCENARIO_EDIT],
            [['name'], 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['country_code', 'phone', 'address', 'birthday'], 'safe']
        ];
    }
}