<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Dialer extends ActiveRecord
{
	const SCENARIO_CREATE = 'create';
	const SCENARIO_EDIT = 'edit';
    public static function tableName()
    {
        return '{{%dialer}}';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['number', 'extend', 'domain', 'action'],
            self::SCENARIO_EDIT => ['id', 'number', 'extend', 'domain', 'action'],
        ];
    }

    public function rules()
    {
        return [
        	['id', 'required', 'on' => self::SCENARIO_EDIT],
            [['number', 'extend', 'domain', 'action'], 'required'],
        ];
    }
}
