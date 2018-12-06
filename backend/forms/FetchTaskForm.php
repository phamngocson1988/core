<?php
namespace backend\forms;

use yii\base\Model;
use common\models\Task;
use common\models\User;
use Yii;

class FetchTaskForm extends Model
{
    public $created_by;
    public $assignee;
    public $status = [];

    public $order_by;

    private $_command;

    public function rules()
    {
        return [
            [['created_by', 'assignee', 'order_by'], 'trim'],
            ['status', 'filter', 'filter' => 'array_filter'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'created_by' => Yii::t('app', 'creator'),
            'assignee' => Yii::t('app', 'assignee'),
            'status' => Yii::t('app', 'status'),
        ];
    }

    public function fetch()
    {
        if ($this->validate()) {
            $command = $this->getCommand();
            return $command->all();
        }
        return false;        
    }

    protected function createCommand()
    {
        $command = Task::find();
        
        if ($this->created_by) {
            $command->orWhere(['created_by' => $this->created_by]);
        }
        if ($this->assignee) {
            $command->orWhere(['assignee' => $this->assignee]);
        }
        if (!empty($this->status)) {
            $command->andWhere(['in', 'status', $this->status]);
        }

        if ($this->order_by) {
            $command->orderBy($this->order_by);
        } else {
            $command->orderBy('id desc');    
        }
        
        return $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }

    public function getStatus()
    {
        return Task::getStatusList();
    }

    public function getCreator()
    {
        return User::findOne($this->created_by);
    }

    public function getAssignee()
    {
        return User::findOne($this->assignee);
    }
}