<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\SystemLog;

class FetchSystemLogForm extends Model
{
    public $user_id;
    public $action;
    public $description;
    public $from_date;
    public $to_date;

    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = SystemLog::find();
        $command->orderBy('id desc');
        if ($this->user_id) {
            $command->andWhere(['user_id' => $this->user_id]);
        }
        if ($this->action) {
            $command->andWhere(['action' => $this->action]);
        }
        if ($this->from_date) {
            $command->andWhere(['>=', 'created_at', $this->from_date]);
        }
        if ($this->to_date) {
            $command->andWhere(['<=', 'created_at', $this->to_date]);
        }
        if ($this->description) {
            $command->andWhere(['like', 'description', $this->description]);
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
}
