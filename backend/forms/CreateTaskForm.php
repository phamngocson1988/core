<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Task;
use common\models\User;

class CreateTaskForm extends Model
{
    public $title;
    public $description;
    public $start_date;
    public $due_date;
    public $assignee;

    /** @var User **/
    protected $_assignee;

    public function rules()
    {
        return [
            [['title'], 'required'],
            ['assignee', 'validateAssignee'],
            [['description', 'start_date', 'due_date', 'assignee'], 'safe']
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
                $task = new Task();
                $task->title = $this->title;
                $task->description = $this->description;
                $task->start_date = $this->start_date;
                $task->due_date = $this->due_date;
                $task->assignee = $this->assignee;
                $task->created_by = Yii::$app->user->id;
                $task->percent = 0;
                $task->status = Task::STATUS_NEW;
                if ($task->save()) {
                    return $task;
                }
                return false;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
}
