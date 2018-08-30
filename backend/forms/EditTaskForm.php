<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Task;
use common\models\User;

class EditTaskForm extends Model
{
    public $id;
    public $title;
    public $description;
    public $start_date;
    public $due_date;
    public $assignee;
    public $percent;
    public $status;
    
    private $_task;

    public function rules()
    {
        $statusList = $this->getStatusList();
        $statusKeys = array_keys($statusList);
        return [
            [['id', 'title'], 'required'],
            [['description', 'start_date', 'due_date', 'assignee', 'percent'], 'safe'],
            ['status', 'in', 'range' => $statusKeys],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $now = date('Y-m-d H:i:s');
                $task = $this->getTask();
                $task->title = $this->title;
                $task->description = $this->description;
                $task->start_date = $this->start_date;
                $task->due_date = $this->due_date;
                $task->assignee = $this->assignee;
                $task->created_by = Yii::$app->user->id;
                $task->updated_at = $now;
                $task->percent = (int)$this->percent;
                $task->status = $this->status;
                return $task->save();
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }

    public function loadData($id)
    {
        $this->id = $id;
        $task = $this->getTask();
        $this->title = $task->title;
        $this->description = $task->description;
        $this->start_date = $task->start_date;
        $this->due_date = $task->due_date;
        $this->assignee = $task->assignee;
        $this->percent = (int)$task->percent;
        $this->status = $task->status;
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

    public function getAssignee()
    {
        return User::findOne($this->assignee);
    }
}
