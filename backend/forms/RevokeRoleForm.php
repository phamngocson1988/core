<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use common\models\UserRole;
/**
 * RevokeRoleForm
 */
class RevokeRoleForm extends Model
{
    public $role;
    public $user_id;

    private $_user;
    private $_role;

    public function rules()
    {
        return [
            [['role', 'user_id'], 'required'],
            ['role', 'validateRole'],
            ['user_id', 'validateUser'],
        ];
    }

    public function revoke()
    {
        if ($this->validate()) {
            $role = $this->getRole();
            $auth = Yii::$app->authManager;
            if ($auth->revoke($role, $this->user_id)) {
                $userRole = UserRole::find()
                ->where(['user_id' => $this->user_id])
                ->andWhere(['role' => $this->role])
                ->one();
                if ($userRole) $userRole->delete();
                return true;
            }
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
