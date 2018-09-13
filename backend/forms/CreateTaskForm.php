<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Task;

class CreateTaskForm extends Model
{
    public $title;
    public $description;
    public $start_date;
    public $due_date;
    public $assignee;

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description', 'start_date', 'due_date', 'assignee'], 'safe']
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            try {
                $now = date('Y-m-d H:i:s');
                $task = new Task();
                $task->title = $this->title;
                $task->description = $this->description;
                $task->start_date = $this->start_date;
                $task->due_date = $this->due_date;
                $task->assignee = $this->assignee;
                $task->created_by = Yii::$app->user->id;
                $task->created_at = $now;
                $task->updated_at = $now;
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
