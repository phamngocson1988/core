<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\ForumPost;

class FetchForumPostForm extends Model
{
    public $q;
    public $topic_id;
    public $created_by;

    protected $_topic;
    private $_command;
    
    public function attributeLabels()
    {
        return [
            'q' => Yii::t('app', 'keyword'),
            'topic_id' => Yii::t('app', 'topic'),
            'created_by' => Yii::t('app', 'creator'),
        ];
    }
    protected function createCommand()
    {
        $command = ForumPost::find();
        if ($this->topic_id) {
            $command->andWhere(["topic_id" => $this->topic_id]);
        } 
        if ($this->q) {
            $command->andWhere(['like', "content", $this->q]);
        }
        if ($this->created_by) {
            $command->andWhere(["created_by" => $this->created_by]);
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

    public function getTopic() 
    {
        if (!$this->_topic) {
            $this->_topic = ForumTopic::findOne($this->topic_id);
        }
        return $this->_topic;
    }

    public function fetchUser()
    {
        $rows = ForumPost::find()->where(['topic_id' => $this->topic_id])->all();
        $userIds = ArrayHelper::getColumn($rows, 'created_at');
        if ($userIds) {
            $users = ForumPost::findAll($userIds);
            return ArrayHelper::map($users, 'id', 'username');
        }
        return [];
    }
}
