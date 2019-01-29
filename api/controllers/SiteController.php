<?php
namespace api\controllers;

use Yii;
use api\forms\LoginForm;
use api\forms\SignupForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends ActiveController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'login', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'login' => ['post'], 
                    'signup' => ['post']
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        $post = Yii::$app->request->post();
        $model = new LoginForm($post);
        if ($model->login()) {
            $user = $model->getUser();
            $authKey = $user->getAuthKey();
            return [
                'result' => true, 
                'user' => $user->exportData(),
                'auth_key' => $authKey,
            ];
        } else {
            return ['result' => false, 'errors' => $model->getErrors()];
        }
    }

    public function actionSignup()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $model = new SignupForm($post);
            if ($model->signup()) {
                return ['result' => true];
            } else {
                return ['result' => false, 'errors' => $model->getErrors()];
            }
        }
    }
}
