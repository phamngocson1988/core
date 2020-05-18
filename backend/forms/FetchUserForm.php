<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;

class FetchUserForm extends Model
{
    public $q;
    public $status;
    private $_command;

    public function attributeLabels()
    {
        return [
            'q' => Yii::t('app', 'keyword'),
            'status' => Yii::t('app', 'status'),
        ];
    }

    protected function createCommand()
    {
        $command = User::find();
        $userTable = User::tableName();
        if ($this->q) {
            $command->andWhere(['OR',
               ["like", "{$userTable}.name", $this->q],
               ["like", "{$userTable}.username", $this->q],
               ["like", "{$userTable}.email", $this->q],
           ]);
        }

        if ($this->status) {
            $command->where(["{$userTable}.status" => $this->status]);
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

    public function fetchStatus()
    {
        return User::getUserStatus();
    }
}
