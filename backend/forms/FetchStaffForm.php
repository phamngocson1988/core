<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use yii\helpers\ArrayHelper;

class FetchStaffForm extends Model
{
    public $q;
    public $role;
    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    public function attributeLabels()
    {
        return [
            'q' => Yii::t('app', 'keyword'),
            'role' => Yii::t('app', 'role'),
        ];
    }

    protected function createCommand()
    {
        $command = User::find();
        $userTable = User::tableName();
        $authManager = Yii::$app->authManager;
        $authTable = $authManager->assignmentTable;
        $command->innerJoin($authTable, "{$userTable}.id = {$authTable}.user_id");
        $command->select(["{$userTable}.*"]);
        $command->where(["{$userTable}.status" => User::STATUS_ACTIVE]);
        if ($this->q) {
            $command->andWhere(['OR',
               ["like", "{$userTable}.name", $this->q],
               ["like", "{$userTable}.username", $this->q],
               ["like", "{$userTable}.email", $this->q],
           ]);
        }

        if ($this->role) {
            $command->andWhere(["{$authManager}.item_name" => $this->role]);
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

}
