<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

/**
 * CreateRoleForm
 */
class CreateRoleForm extends Model
{
    public $name;
    public $description;

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            ['name', 'validateName'],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $auth = Yii::$app->authManager;
            $role = $auth->createRole($this->name);
            $role->description = $this->description;
            $auth->add($role);
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
                $this->addError($attribute, "$this->name is exist");
            }
        }
    }
}
