<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\User;
use common\models\Affiliate;
use common\models\AffiliateCommission;
use common\models\AffiliateCommissionWithdraw;

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

	public function getAffiliate()
    {
		$owner = $this->owner;
        return $owner->hasOne(Affiliate::className(), ['user_id' => 'id']);
    }

	public function getAffiliateCode()
	{
		$owner = $this->owner;
		$affiliate = $owner->affiliate;
		return $affiliate->code;
	}

	public function affiliateBalance()
	{
		$owner = $this->owner;
		$now = date('Y-m-d H:i:s');
		$income = AffiliateCommission::find()->where([
			'user_id' => $owner->id, 
			'status' => AffiliateCommission::STATUS_VALID, 
		])
		->andWhere(['<=', 'valid_from_date', $now])
		->sum('commission');
		$outcome = AffiliateCommissionWithdraw::find()->where([
			'user_id' => $owner->id, 
			'status' => AffiliateCommissionWithdraw::STATUS_EXECUTED
		])->sum('amount');
		return $income - $outcome;
	}
}