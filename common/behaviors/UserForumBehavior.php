<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\ForumPost;
use common\models\ForumTopic;
use common\models\ForumLike;

class UserForumBehavior extends AttributeBehavior
{
    public function countForumPost() 
    {
        $owner = $this->owner; // User
        return ForumPost::find()->where([
            'created_by' => $owner->id,
        ])->count();
    }

    public function countForumTopic() 
    {
        $owner = $this->owner; // User
        return ForumTopic::find()->where([
            'created_by' => $owner->id,
        ])->count();
    }

    public function isLike($postId) 
    {
        $owner = $this->owner; // User
        return ForumLike::find()->where([
            'created_by' => $owner->id,
            'post_id' => $postId,
        ])->exists();
    }
}
