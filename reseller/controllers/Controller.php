<?php
namespace reseller\controllers;

use Yii;
use common\components\Controller as BaseController;
use reseller\models\UserReseller;
use reseller\components\helpers\Url;

/**
 * Controller
 */
class Controller extends BaseController
{
	public function beforeAction($action) 
	{
	    if (!parent::beforeAction($action)) {
	        return false;
	    }

	    if (Yii::$app->user->isGuest) {
		    $request = Yii::$app->request;
		    $code = $request->get('reseller_code');
		    if (!$code) return false;
		    $reseller = UserReseller::findOne(['code' => $code]);
		    if (!$reseller) return false;
		    $user = $reseller->user;
		    if (!$user) return false;
		    Yii::$app->user->login($user);
	    }
	    return true;
	}

	public function redirect($url, $statusCode = 302)
    {
        return Yii::$app->getResponse()->redirect(Url::to($url), $statusCode);
    }
}