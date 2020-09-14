<?php
namespace supplier\components;

use Yii;

class User extends \yii\web\User
{
	public $advanceModeKey = 'advanceModeKey';
	
	public function isAdvanceMode()
	{
		return !!Yii::$app->session->get($this->advanceModeKey, false);
	}


}