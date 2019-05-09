<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use yii\helpers\ArrayHelper;

/**
 * FetchUserForm
 */
class FetchUserForm extends Model
{
    public $q;
    public $status;
    public $role;
    private $_command;

    public function rules()
    {
        return [
            [['q', 'status'], 'trim'],
        ];
    }

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = User::find();

        if ($this->q) {
            $command->orWhere(['like', 'name', $this->q]);
            $command->orWhere(['like', 'username', $this->q]);
            $command->orWhere(['like', 'email', $this->q]);
        }
        if ((string)$this->status !== "") {
            $command->andWhere(['status' => $this->status]);
        }

        if ($this->role) {
            $authManager = Yii::$app->authManager;
            $filterRole = sprintf("%s.%s = %s.%s", $authManager->assignmentTable, 'user_id', User::tableName(), 'id');
            $command->join('LEFT JOIN', $authManager->assignmentTable, $filterRole)->andWhere(["IN", "$authManager->assignmentTable.item_name", (array)$this->role]);
        }
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function getUserStatus()
    {
        return User::getUserStatus();
    }

    public function getRoles()
    {
        $roles = Yii::$app->authManager->getRoles();
        return ArrayHelper::map($roles, 'name', 'description');
    }

    public function getManagerRoles()
    {
        $roles = $this->getRoles();
        unset($roles['customer']);
        return $roles;
    }
}
