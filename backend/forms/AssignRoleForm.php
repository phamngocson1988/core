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
            [['role', 'user_id'], 'required'],
            ['role', 'validateRole'],
            ['user_id', 'validateUser'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'user'),
            'role' => Yii::t('app', 'role'),
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $role = $this->getRole();
            $user = $this->getUser();
            $auth = Yii::$app->authManager;
            return $auth->assign($role, $this->user_id);
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

    public function fetchRoles()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $names = ArrayHelper::map($roles, 'name', 'description');
        return $names;
    }

    public function fetchUsers()
    {
        $users = User::find()->where(['status' => User::STATUS_ACTIVE])->select(['id', 'email'])->all();
        return ArrayHelper::map($users, 'id', 'email');
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
