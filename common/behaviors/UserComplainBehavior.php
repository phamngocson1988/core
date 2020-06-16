<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\ComplainFollow;

class UserComplainBehavior extends AttributeBehavior
{
    public function isFollow($complainId) 
    {
        $owner = $this->owner; // User
        return ComplainFollow::find()->where([
            'user_id' => $owner->id,
            'complain_id' => $complainId
        ])->exists();
    }
}
