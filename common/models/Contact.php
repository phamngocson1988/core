<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Contact extends ActiveRecord
{
	const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';
    
    public $group_ids = [];

    public static function tableName()
    {
        return '{{%contact}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['user_id', 'phone', 'name', 'description', 'group_ids'];
        $scenarios[self::SCENARIO_EDIT] = ['id', 'phone', 'name', 'description', 'group_ids'];
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

    public function getContactGroups()
    {
        return $this->hasMany(ContactGroup::className(), ['contact_id' => 'id']);
    }

    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id' => 'group_id'])
            ->viaTable(ContactGroup::tableName(), ['contact_id' => 'id']);
    }

    public function deleteGroups()
    {
        $groups = $this->contactGroups;
        foreach ($groups as $group) $group->delete();
    }
}
