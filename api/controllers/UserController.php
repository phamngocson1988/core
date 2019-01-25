<?php
namespace api\controllers;

use Yii;

class UserController extends ActiveController
{
	public function actionCurrent()
	{
		return $this->asJson(['user' => Yii::$app->user->getIdentity()]);
	}
}