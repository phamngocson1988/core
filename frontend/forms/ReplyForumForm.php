<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\ForumTopic;
use frontend\models\ForumPost;

class ReplyForumForm extends Model
{
    public $topic_id;
    public $content;

    protected $_topic;

    public function rules()
    {
        return [
            [['topic_id', 'content'], 'required'],
            ['topic_id', 'validateTopic']
        ];
    }

    public function validateTopic($attribute, $params = [])
    {
        $topic = $this->getTopic();
        if (!$topic) {
            $this->addError($attribute, 'This topic is not exist');
        }
    }

    public function getTopic()
    {
        if (!$this->_topic) {
            $this->_topic = ForumTopic::findOne($this->topic_id);
        }
        return $this->_topic;
    }

    public function reply()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $post = new ForumPost();
            $post->topic_id = $this->topic_id;
            $post->content = $this->content;
            $post->save();

            $topic = $this->getTopic();
            $topic->touch('updated_at');
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('content', $e->getMessage());
            return false;
        }
    }

}
