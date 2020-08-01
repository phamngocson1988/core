<?php
namespace frontend\controllers;

use Yii;

class Controller extends \yii\web\Controller 
{
	public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) return false;
        // Log user activity
        if (Yii::$app->user->isGuest) return true;
        $user = Yii::$app->user->getIdentity();
        $user->logLastActivity();
        return true;
    }
}