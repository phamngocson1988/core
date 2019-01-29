<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;

class UserController extends ActiveController
{
	public function behaviors()
    {
    	$behaviors = parent::behaviors();
    	$behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
        return $behaviors;
    }

	public function actionCurrent()
	{
		return ['user' => Yii::$app->user->getIdentity()];
	}
}