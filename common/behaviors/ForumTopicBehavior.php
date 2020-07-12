<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\ForumPost;

class ForumTopicBehavior extends AttributeBehavior
{
    public function countPost()
    {
    	$owner = $this->owner; // ForumTopic
    	return ForumPost::find()->where([
    		'topic_id' => $owner->id
    	])->count();
    }
}
