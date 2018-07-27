<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
/**
 * RevokeRoleForm
 */
class RevokeRoleForm extends Model
{
    public $role;
    public $user_id;

    private $_user;
    private $_role;

    const SCENARIO_REVOKE = 'revoke';
    const SCENARIO_REVOKEALL = 'revoke_all';

    public function rules()
    {
        return [
            [['role', 'user_id'], 'required', 'on' => self::SCENARIO_REVOKE],
            [['user_id'], 'required', 'on' => self::SCENARIO_REVOKEALL],
            ['role', 'validateRole', 'on' => self::SCENARIO_REVOKE],
            ['user_id', 'validateUser'],
        ];
    }

    public function revoke()
    {
        if ($this->validate()) {
            $role = $this->getRole();
            $auth = Yii::$app->authManager;
            return $auth->revoke($role, $this->user_id);
        }
        return false;
    }

    public function revokeAll()
    {
        if ($this->validate()) {
            $auth = Yii::$app->authManager;
            return $auth->revokeAll($this->user_id);
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

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($this->user_id);
        }

        return $this->_user;
    }

    public function getRole()
    {
        if ($this->_role === null) {
            $auth = Yii::$app->authManager;
            $this->_role = $auth->getRole($this->role);
        }

        return $this->_role;
    }
}
