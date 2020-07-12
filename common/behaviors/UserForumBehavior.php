<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\ForumPost;
use common\models\ForumTopic;

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
}
