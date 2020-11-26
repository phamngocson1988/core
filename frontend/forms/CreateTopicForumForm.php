<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\ForumPost;
use frontend\models\ForumTopic;
use frontend\models\ForumCategory;
use frontend\models\User;

class CreateTopicForumForm extends Model
{
    public $subject;
    public $content;
    public $category_id;

    public function rules()
    {
        return [
            [['subject', 'content', 'category_id'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => Yii::t('app', 'Subject'),
            'content' => Yii::t('app', 'Content'),
            'category_id' => Yii::t('app', 'Forum'),
        ];
    }

    public function create()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $topic = new ForumTopic();
            $topic->subject = $this->subject;
            $topic->category_id = $this->category_id;
            $topic->language = Yii::$app->language;
            $topic->save();

            $post = new ForumPost();
            $post->topic_id = $topic->id;
            $post->content = $this->content;
            $post->save();

            $user = $this->getUser();
            $user->plusPoint(20, sprintf("Create new forum topic %s", $this->subject));
            
            $transaction->commit();
            return $topic;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', $e->getMessage());
            return false;
        }
    }

    public function fetchCategory()
    {
        $models = ForumCategory::find()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }
}
