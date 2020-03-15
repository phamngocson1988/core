<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;

class FetchUserForm extends Model
{
    public $q;

    private $_command;

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
        }
        // $command->andWhere(['status' => User::STATUS_ACTIVE]);
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
