<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use common\models\LoginLog;

class FetchLoginLogForm extends Model
{
    public $user_id;
    public $date_from;
    public $date_to;

    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = LoginLog::find();
        $command->with('user');
        $command->orderBy(['created_at' => SORT_DESC]);
        if ($this->user_id) {
            $command->andWhere(['user_id' => $this->user_id]);
        }
        if ($this->date_from) {
            $command->andWhere(['>=', "created_at", $this->date_from . ' 00:00:00']);
        }
        if ($this->date_to) {
            $command->andWhere(['<=', "created_at", $this->date_to . ' 23:59:59']);
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

    public function getUser()
    {
        return User::findOne($this->user_id);
    }
}
