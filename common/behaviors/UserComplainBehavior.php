<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\ComplainFollow;
use common\models\Complain;

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

    public function countComplain()
    {
    	$owner = $this->owner; // User
    	return Complain::find()->where([
    		'user_id' => $owner->id
    	])->count();
    }
}
