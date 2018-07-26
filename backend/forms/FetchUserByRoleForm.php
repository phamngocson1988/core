<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;

/**
 * FetchUserByRoleForm
 */
class FetchUserByRoleForm extends Model
{
    public $role;
    private $_command;

    public function rules()
    {
        return [
            [['role'], 'trim'],
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
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }
}
