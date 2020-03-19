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

    public function rules()
    {
        return [
            ['user_id', 'required', 'message' => 'Nhân viên không được để trống'],
            ['role', 'required', 'message' => 'Tên vai trò không được để trống'],
            ['role', 'validateRole'],
            ['user_id', 'validateUser'],
        ];
    }

    public function assign()
    {
        if ($this->validate()) {
            $role = $this->getRole();
            $user = $this->getUser();
            $auth = Yii::$app->authManager;
            $auth->assign($role, $this->user_id);
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
                return;
            }
            if (!Yii::$app->user->can($this->role)) {
                $this->addError($attribute, sprintf('Bạn không có đủ quyền hạn để thêm vai trò %s cho nhân viên khác', $this->role));
                return;
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
