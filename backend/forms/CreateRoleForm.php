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
            Yii::$app->syslog->log('create_role', 'create new role', $role);
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
}
