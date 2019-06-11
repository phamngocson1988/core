<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

class Group extends ActiveRecord
{
	const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';
    
    const STATUS_ACTIVE = 1;
	const STATUS_DISACTIVE = 0;

    public static function tableName()
    {
        return '{{%group}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['name', 'description', 'status', 'user_id'];
        $scenarios[self::SCENARIO_EDIT] = ['id', 'name', 'description', 'status'];
        return $scenarios;
    }

    public function rules()
    {
        return [
        	['id', 'required', 'on' => self::SCENARIO_EDIT],
        	[['name', 'user_id'], 'required', 'on' => self::SCENARIO_CREATE],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['description', 'trim']
        ];
    }

    public function getContactGroups()
    {
        return $this->hasMany(ContactGroup::className(), ['group_id' => 'id']);
    }

    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['id' => 'contact_id'])
            ->viaTable(ContactGroup::tableName(), ['group_id' => 'id']);
    }

    public function getNumberContacts()
    {
        $user = $this->getContactGroups();
        return $user->count();
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->getNumberContacts()) {
                $this->addError('id', 'This group is including some contacts');
                return false;
            }
            return true;
        }
        return false;
    }

    public function afterDelete()
    {
        foreach ($this->contacts as $user) {
            $user->delete();
        }
        parent::afterDelete();
    }

    public static function find()
	{
		return new GroupQuery(get_called_class());
    }
    
}

class GroupQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['user_id' => Yii::$app->user->id]);
        parent::init();
    }
}