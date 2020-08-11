<?php
namespace backend\behaviors;
use yii\behaviors\AttributeBehavior;
use backend\components\notifications\GameNotification;

class GameNotificationBehavior extends AttributeBehavior
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
	        GameNotification::create($key, [
	            'game' => $owner,
	            'userId' => $userId
	        ])->send();
    	}
    }

}
