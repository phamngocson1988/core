<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\ForumTopic;

class ForumCategoryBehavior extends AttributeBehavior
{
    public function countTopic()
    {
    	$owner = $this->owner; // ForumCategory
    	return ForumTopic::find()->where([
    		'category_id' => $owner->id
    	])->count();
    }

    public function getNewestTopic()
    {
    	$owner = $this->owner; // ForumCategory
    	return ForumTopic::find()
    	->where(['category_id' => $owner->id])
    	->orderBy(['id' => SORT_DESC])
    	->one();
    }
}
