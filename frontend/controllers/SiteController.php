<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Html;

use frontend\forms\LoginForm;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return json_encode(['status' => true, 'user_id' => Yii::$app->user->id]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
        }
    }

    public function actionSignup()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);
        $model = new \frontend\forms\SignupForm();

        if ($model->load($request->post()) && $model->validate()) {
            $user = $model->signup();
            if ($user) {
                Yii::$app->user->login($user);
                return json_encode(['status' => true]);
            } else {
                return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => 'There is something wrong. Please contact our staff.']);
            }
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
        }
    }

    public function actionRequestPasswordReset()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!Yii::$app->user->isGuest) return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => []]);
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return json_encode(['status' => true]);
            } else {
                return json_encode(['status' => false, 'errors' => 'Sorry, we are unable to reset password for the provided email address.']);
            }
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
        }
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
