<?php
namespace common\behaviors;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use common\models\UserBadge;

class UserBadgeBehavior extends AttributeBehavior
{
    public function addBadge($badge, $key = '', $description = '') 
    {
        $owner = $this->owner; // User
        $log = new UserBadge();
        $log->user_id = $owner->id;
        $log->badge = $badge;
        $log->key = $key;
        $log->description = $description;
        return $log->save();
    }

    public function hasBadge($badge) 
    {
        $owner = $this->owner; // User
        return UserBadge::find()->where([
            'badge' => $badge,
            'user_id' => $owner->id,
        ])->exists();
    }
}
