<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ForumPost;
use yii\helpers\ArrayHelper;

class EditForumPostForm extends Model
{
    public $id;
    public $topic_id;
    public $content;
    public $is_approved;
    public $status;

    protected $_post;

    public function rules()
    {
        return [
            [['id', 'content', 'status', 'is_approved'], 'required'],
            ['id', 'validatePost'],
            ['topic_id', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => Yii::t('app', 'content'),
            'is_approved' => Yii::t('app', 'approve'),
            'status' => Yii::t('app', 'status'),
        ];
    }

    public function validatePost($attribute, $params = []) 
    {
        $post = $this->getPost();
        if (!$post) {
            $this->addError($attribute, Yii::t('app', 'post_is_not_exist'));
        }
    }
    public function update()
    {
        $post = $this->getPost();
        $post->content = $this->content;
        $post->is_approved = $this->is_approved;
        $post->status = $this->status;
        return $post->save();
    }

    public function fetchStatus()
    {
        return ForumPost::getStatusList();
    }

    public function fetchApproved()
    {
        return ForumPost::getApproveStatus();
    }

    public function loadData()
    {
        $post = $this->getPost();
        $this->content = $post->content;
        $this->is_approved = $post->is_approved;
        $this->status = $post->status;
        $this->topic_id = $post->topic_id;
    }

    public function getPost()
    {
        if (!$this->_post) {
            $this->_post = ForumPost::findOne($this->id);
        }
        return $this->_post;
    }
}
