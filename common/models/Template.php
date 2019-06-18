<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

class Template extends ActiveRecord
{
	const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    const STATUS_ACTIVE = 1;
    const STATUS_DISACTIVE = 0;
    
    public static function tableName()
    {
        return '{{%template}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['user_id', 'title', 'content', 'status'];
        $scenarios[self::SCENARIO_EDIT] = ['id', 'user_id', 'title', 'content', 'status'];
        return $scenarios;
    }

    public function rules()
    {
        return [
        	['id', 'required', 'on' => self::SCENARIO_EDIT],
        	[['user_id', 'title', 'content', 'status'], 'required'],
        ];
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public static function find()
    {
        return new TemplateQuery(get_called_class());
    }
}

class TemplateQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['user_id' => Yii::$app->user->id]);
        parent::init();
    }
}
