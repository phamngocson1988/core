<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use yii\helpers\ArrayHelper;
/**
 * AssignRoleForm
 */
class AssignRoleForm extends Model
{
    public $role;
    public $user_id;

    private $_user;
    private $_role;

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
            $role = $this->getRole();
            $user = $this->getUser();
            if ($auth->assign($role, $this->user_id)) {
                Yii::$app->syslog->log('assign_role', 'assign role to user', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role' => $role->name
                ]);
                return true;
            }
            return true;
        }
        return false;
    }

    public function validateRole($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $role = $this->getRole();
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

    public function getRole()
    {
        if (!$this->_role) {
            $auth = Yii::$app->authManager;
            $this->_role = $auth->getRole($this->role);
        }
        return $this->_role;
    }
}
