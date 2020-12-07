<?php
namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\UnauthorizedHttpException;

class SiteController extends Controller
{
	public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['authenticator'] = [
	        'class' => HttpBearerAuth::className(),
	        'except' => ['login', 'error']
	    ];
	    return $behaviors;
	}

	public function actions()
    {
        return [
            'error' => [
                'class' => 'api\components\ErrorAction',
            ],
        ];
    }

	public function actionLogin()
	{
		$request = Yii::$app->request;
		$form = new \api\forms\LoginForm([
			'username' => $request->post('username'),
			'password' => $request->post('password'),
		]);
		$token = $form->login();
		if ($token) {
			return ['access_token' => $token];
		} else {
			throw new UnauthorizedHttpException("Your request was made with invalid credentials", 1);
		}
	}
}