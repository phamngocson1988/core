<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Task;
use common\models\User;

class UpdateTaskStatusForm extends Model
{
    public $id;
    public $status;
    
    private $_task;

    public function rules()
    {
        $statusList = $this->getStatusList();
        $statusKeys = array_keys($statusList);
        return [
            [['id'], 'required'],
            ['status', 'in', 'range' => $statusKeys],
        ];
    }

    public function inprogress()
    {
        $this->status = Task::STATUS_INPROGRESS;
        if ($this->validate()) {
            try {
                $now = date('Y-m-d H:i:s');
                $task = $this->getTask();
                $task->updated_at = $now;
                $task->status = $this->status;
                return $task->save();
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function done()
    {
        $this->status = Task::STATUS_DONE;
        if ($this->validate()) {
            try {
                $now = date('Y-m-d H:i:s');
                $task = $this->getTask();
                $task->updated_at = $now;
                $task->percent = 100;
                $task->status = $this->status;
                return $task->save();
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function invalid()
    {
        $this->status = Task::STATUS_INVALID;
        if ($this->validate()) {
            try {
                $now = date('Y-m-d H:i:s');
                $task = $this->getTask();
                $task->updated_at = $now;
                $task->status = $this->status;
                return $task->save();
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    protected function getTask()
    {
        if ($this->_task === null) {
            $this->_task = Task::findOne($this->id);
        }
        return $this->_task;
    }

    public function getStatusList()
    {
        return Task::getStatusList();
    }
}
