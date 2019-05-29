<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Dialer extends ActiveRecord
{
	const SCENARIO_CREATE = 'create';
	const SCENARIO_EDIT = 'edit';

    const ACTION_CALL = 'call';
    const ACTION_SMS = 'sms';

    const STATUS_DISACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return '{{%dialer}}';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['number', 'extend', 'domain', 'action', 'status'],
            self::SCENARIO_EDIT => ['id', 'number', 'extend', 'domain', 'action', 'status'],
        ];
    }

    public function rules()
    {
        return [
        	['id', 'required', 'on' => self::SCENARIO_EDIT],
            [['number', 'extend', 'domain', 'action'], 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE]
        ];
    }

    public function getUsers()
    {
        return $this->hasMany(CustomerDialer::className(), ['dialer_id' => 'id']);
    }

    public function getNumberUsers()
    {
        $user = $this->getUsers();
        return $user->count();
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->getNumberUsers()) {
                $this->addError('id', 'This dialer is used by some users');
                return false;
            }
            return true;
        }
        return false;
    }

    public function afterDelete()
    {
        foreach ($this->users as $user) {
            $user->delete();
        }
        parent::afterDelete();
    }
}
