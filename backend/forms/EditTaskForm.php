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
    

    /** @var Task **/
    private $_task;
    /** @var User **/
    protected $_assignee;
    public function rules()
    {
        $statusList = $this->getStatusList();
        $statusKeys = array_keys($statusList);
        return [
            [['id', 'title'], 'required'],
            ['assignee', 'validateAssignee'],
            [['description', 'start_date', 'due_date', 'assignee', 'percent'], 'safe'],
            ['status', 'in', 'range' => $statusKeys],
        ];
    }

    public function validateAssignee($attribute, $params)
    {
        if (!$this->assignee) return null;
        $assignee = $this->getAssignee();
        if (!$assignee) {
            $this->addError($attribute, Yii::t('app', 'invalid_user'));
        }
    }

    public function getAssignee()
    {
        if (!$this->assignee) return null;
        if (!$this->_assignee) {
            $this->_assignee = User::findOne($this->assignee);
        }
        return $this->_assignee;
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $task = $this->getTask();
                $task->title = $this->title;
                $task->description = $this->description;
                $task->start_date = $this->start_date;
                $task->due_date = $this->due_date;
                $task->assignee = $this->assignee;
                $task->created_by = Yii::$app->user->id;
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
}
