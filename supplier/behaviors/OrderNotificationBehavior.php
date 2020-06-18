<?php
namespace supplier\behaviors;
use yii\behaviors\AttributeBehavior;
use supplier\components\notifications\OrderNotification;

class OrderNotificationBehavior extends AttributeBehavior
{
	/**
	 * @param string $key - Key of notification
	 * @param array|int $userIds array of user id will be received notification
	 */
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