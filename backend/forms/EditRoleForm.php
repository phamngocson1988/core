<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\db\Query;

/**
 * EditRoleForm
 */
class EditRoleForm extends Model
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
            $role = $auth->getRole($this->name);
            $role->description = $this->description;
            $auth->update($this->name, $role);

            // Check parent
            $parents = $this->getChildrenList();
            foreach ($parents as $parent => $children) {
                if (in_array($role->name, $children)) {
                    $parentRole = $auth->getRole($parent);
                    $auth->removeChild($parentRole, $role);
                }
            }
            
            // New parent
            if ($this->parent_role) {
                $parentRole = $auth->getRole($this->parent_role);
                $auth->addChild($parentRole, $role);
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
            if (!$role) {
                $this->addError($attribute, "$this->role is not exist");
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
        $roles = $auth->getRoles(); 
        $list = ArrayHelper::map($roles, 'name', 'description');
        return $list;
    }

    public function loadData()
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($this->name);
        $this->description = $role->description;

        $parents = $this->getChildrenList();
        foreach ($parents as $parent => $children) {
            if (in_array($this->name, $children)) {
                $this->parent_role = $parent;
            }
        }
    }

    protected function getChildrenList()
    {
        $auth = Yii::$app->authManager;
        $query = (new Query())->from($auth->itemChildTable);
        $parents = [];
        foreach ($query->all($auth->db) as $row) {
            $parents[$row['parent']][] = $row['child'];
        }

        return $parents;
    }
}
