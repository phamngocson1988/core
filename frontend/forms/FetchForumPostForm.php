<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\ForumPost;

class FetchForumPostForm extends Model
{
    public $q;
    public $topic_id;
    public $created_by;

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
        $command->where([
            'is_approved' => ForumPost::APPROVED_YES,
            'status' => ForumPost::STATUS_ACTIVE,
        ]);
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
}
