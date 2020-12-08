<?php
namespace api\behaviors;
use yii\behaviors\AttributeBehavior;
use api\components\notifications\OrderNotification;

class OrderNotificationBehavior extends AttributeBehavior
{
	public function pushNotification($key, $userIds)
    {
    	if (is_numeric($userIds)) {
    		$userIds = (array)$userIds;
    	}
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
