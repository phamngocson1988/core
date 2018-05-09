<?php

namespace client\forms;

use Yii;
use yii\base\Model;
use client\models\User;
use yii\helpers\ArrayHelper;
/**
 * AssignRoleForm
 */
class AssignRoleForm extends Model
{
    public $role;
    public $user_id;

    private $_user;

    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => ['role', 'user_id'],
            self::SCENARIO_EDIT => ['role'],
        ];
    }

    public function rules()
    {
        return [
            [['role', 'user_id'], 'required', 'on' => self::SCENARIO_ADD],
            [['user_id'], 'required', 'on' => self::SCENARIO_EDIT],
            ['role', 'validateRole'],
            ['user_id', 'validateUser'],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($this->role);
            $auth->revokeAll($this->user_id);
            if ($role) {
                $auth->assign($role, $this->user_id);
            }
            return true;
        }
        return false;
    }

    public function validateRole($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($this->role);
            if (!$role) {
                $this->addError($attribute, Yii::t('app', 'role_not_exist', ['role' => $role]));
            }
        }
    }

    public function validateUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, Yii::t('app', 'user_not_exist', ['user' => $user]));
            }
        }
    }

    public function getRoles()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $names = ArrayHelper::map($roles, 'name', 'description');
        return $names;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($this->user_id);
        }

        return $this->_user;
    }
}
