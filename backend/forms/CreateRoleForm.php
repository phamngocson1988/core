<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * CreateRoleForm
 */
class CreateRoleForm extends Model
{
    public $name;
    public $description;
    public $parent_role;

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            ['name', 'validateName'],
            ['parent_role', 'trim'],
            ['parent_role', 'validateParent'],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $auth = Yii::$app->authManager;
            $role = $auth->createRole($this->name);
            $role->description = $this->description;
            $auth->add($role);

            if ($this->parent_role) {
                $parent = $auth->getRole($this->parent_role);
                $auth->addChild($parent, $role);
            }
            return true;
        }
        return false;
    }

    public function validateName($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($this->name);
            if ($role) {
                $this->addError($attribute, Yii::t('app', 'role_exist', ['role' => $this->name]));
            }
        }
    }

    public function validateParent($attribute, $params)
    {
        if (!$this->parent_role) return;
        if (!$this->hasErrors()) {
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($this->parent_role);
            if (!$role) {
                $this->addError($attribute, Yii::t('app', 'role_not_exist', ['role' => $this->parent_role]));
            }
        }
    }

    public function getAvailableParent()
    {
        $auth = Yii::$app->authManager; 
        $roles = $auth->getRoles(); //echo '<pre>';print_r($roles) ; die;
        $list = ArrayHelper::map($roles, 'name', 'description');
        return $list;
    }
}
