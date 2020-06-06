<?php
namespace website\behaviors;
use yii\behaviors\AttributeBehavior;
use website\components\notifications\DepositNotification;

class DepositNotificationBehavior extends AttributeBehavior
{
	public function pushNotification($key, $userIds)
    {
    	if (!count($userIds)) return;
	    $owner = $this->owner; // transaction
    	foreach ($userIds as $userId) {
	        DepositNotification::create($key, [
	            'transaction' => $owner,
	            'userId' => $userId
	        ])->send();
    	}
    }
}
