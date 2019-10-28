<?php
namespace backend\models;

use Yii;

class UserCommissionWithdraw extends \common\models\UserCommissionWithdraw
{
	public function approve()
	{
		if ($this->isRequest()) {
			$this->status = self::STATUS_APPROVED;
			$this->approved_by = Yii::$app->user->id;
			$this->approved_at = date('Y-m-d H:i:s');
			return $this->save();
		}
		return false;
	}

	public function disapprove()
	{
		if ($this->isExecuted()) return false;
		return $this->delete();
	}

	public function execute()
	{
		if ($this->isApprove()) {
			$this->status = self::STATUS_EXECUTED;
			$this->executed_by = Yii::$app->user->id;
			$this->executed_at = date('Y-m-d H:i:s');
			return $this->save();
		}
		return fasle;
	}
}