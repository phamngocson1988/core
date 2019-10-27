<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\User;
use common\models\UserAffiliate;

class UserAffiliateBehavior extends AttributeBehavior
{
	public function getAffiliateMembers($from = null, $to = null)
	{
		$owner = $this->owner;
        $command = $owner->hasMany(User::className(), ['affiliated_with' => 'id']);
        if ($from) {
			$command->andWhere('created_at >= :from', [':from' => $from]);
        }
        if ($to) {
			$command->andWhere('created_at <= :to', [':to' => $to]);
        }
        return $command;
	}
}