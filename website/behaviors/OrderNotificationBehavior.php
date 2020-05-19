<?php
namespace website\behaviors;
use yii\behaviors\AttributeBehavior;
use website\components\notifications\OrderNotification;

class OrderNotificationBehavior extends AttributeBehavior
{
	public function pushNotification($key, $userIds)
    {
    	if (!count($userIds)) return;
	    $owner = $this->owner; // order
    	foreach ($userIds as $userId) {
	        OrderNotification::create($key, [
	            'order' => $owner,
	            'userId' => $userId
	        ])->send();
    	}
    }
}
