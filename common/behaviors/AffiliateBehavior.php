<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\User;
use common\models\Affiliate;

class AffiliateBehavior extends AttributeBehavior
{
	public function isAffiliate()
	{
		$owner = $this->owner;
		return Affiliate::find()
		->where(['user_id' => $owner->id])
		->andWhere(['status' => Affiliate::STATUS_ENABLE])
		->exists();
	}

	public function hasPendingAffiliateRequest()
	{
		$owner = $this->owner;
		return Affiliate::find()
		->where(['user_id' => $owner->id])
		->andWhere(['status' => Affiliate::STATUS_DISABLE])
		->exists();
	}
}