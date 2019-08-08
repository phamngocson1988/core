<?php
namespace frontend\models;

use Yii;

class UserCommissionWithdraw extends \common\models\UserCommissionWithdraw
{
	public function init()
	{
		$this->user_id = Yii::$app->user->id;
		$this->status = self::STATUS_REQUEST;
	}

	public function rules()
    {
        return [
            [['amount'], 'required'],
            [['amount'], 'number'],
        ];
    }
}