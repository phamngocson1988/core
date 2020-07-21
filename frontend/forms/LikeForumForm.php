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

    public function rules()
    {
        return [
            [['post_id', 'user_id'], 'required'],
        ];
    }

    public function like()
    {
        $exists = ForumLike::find()->where([
          'user_id' => $this->user_id,
          'post_id' => $this->post_id,
        ])->exists();
        if ($exists) return true;
        
        $like = new ForumLike([
            'post_id' => $this->post_id,
        ]);
        return $like->save();
    }

    public function dislike()
    {
        $like = ForumLike::find()->where([
            'user_id' => $this->user_id,
            'post_id' => $this->post_id,
        ])->one();
        return $like ? $like->delete() : true;
    }

}
