<?php
namespace backend\components\notifications;

use Yii;
use webzop\notifications\Notification;

class TaskNotification extends Notification
{
    const KEY_NEW_TASK = 'new_task';

    /**
     * @var \common\models\Task
     */
    public $task;


    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key){
            case self::KEY_NEW_TASK:
                return Yii::t('app', 'task_{title}_just_assign_to_you', ['title' => $this->task->title]);
            
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute()
    {
        return ['/task/index', 'assignee' => $this->userId, 'status' => ['new']];
    }
}