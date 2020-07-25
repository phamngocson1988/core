<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\ForumLike;
use frontend\models\ForumPost;

class LikeForumForm extends Model
{
    public $post_id;
    public $user_id;

    protected $_post;

    public function rules()
    {
        return [
            [['post_id', 'user_id'], 'required'],
            ['post_id', 'validatePost'],
            ['user_id', 'validateUser'],
        ];
    }

    public function validatePost($attribute, $params = [])
    {
        $post = $this->getPost();
        if (!$post) {
            $this->addError($attribute, 'The post is not exist');
        }
    }

    public function validateUser($attribute, $params = [])
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, 'You have to login to like/dislike a post');
        }
    }

    public function like()
    {
        $exists = ForumLike::find()->where([
          'created_by' => $this->user_id,
          'post_id' => $this->post_id,
        ])->exists();
        if ($exists) return true;
        
        $like = new ForumLike([
            'post_id' => $this->post_id,
        ]);
        $post = $this->getPost();
        $result = $like->save();
        if ($post->created_by != $this->user_id) {
            $creator = $post->sender;
            $creator->plusPoint(1, 'Your post have just been liked by another');
        }
        return $result;
    }

    public function dislike()
    {
        $like = ForumLike::find()->where([
            'created_by' => $this->user_id,
            'post_id' => $this->post_id,
        ])->one();
        return $like ? $like->delete() : true;
    }

    public function getPost()
    {
        if (!$this->_post) {
            $this->_post = ForumPost::findOne($this->post_id);
        }
        return $this->_post;
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

}
