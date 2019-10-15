<?php
namespace supplier\controllers;

use Yii;
use supplier\controllers\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use supplier\forms\LoginForm;
use supplier\forms\ActivateUserForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'activate'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'login.tpl';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login.tpl', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    public function actionActivate()
    {
        $this->layout = 'login.tpl';
        $request = Yii::$app->request;
        $id = $request->get('id');
        $key = $request->get('activation_key');
        $model = new ActivateUserForm(['id' => $id, 'activation_key' => $key]);
        if ($request->isPost) {
            $model->setScenario(ActivateUserForm::SCENARIO_CREATE_PASS);
            if ($model->load($request->post()) && $user = $model->activate()) {
                // login
                $loginForm = new LoginForm(['username' => $user->username, 'password' => $model->password]);
                if ($loginForm->login()) {
                    Yii::$app->session->setFlash('success', 'Success!');
                    return $this->goHome();
                } else {
                    Yii::$app->session->setFlash('error', $loginForm->getErrorSummary(true));
                }
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->setScenario(ActivateUserForm::SCENARIO_CHECK_KEY);
            if (!$model->validate()) {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }

        return $this->render('activate.tpl', ['model' => $model]);
    }
}