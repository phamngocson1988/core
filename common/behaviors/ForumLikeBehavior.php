<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\ForumPost;
use common\models\ForumLike;
use common\models\User;

class ForumLikeBehavior extends AttributeBehavior
{
    public function getUserLike()
    {
    	$owner = $this->owner; // ForumPost
    	return $owner->hasMany(User::className(), ['id' => 'created_by'])
            ->viaTable(ForumLike::tableName(), ['post_id' => 'id']);
    }

    public function countUserLike()
    {
    	return $this->getUserLike()->count();
    }
}
