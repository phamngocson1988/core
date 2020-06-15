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
use yii\helpers\ArrayHelper;

use frontend\models\Operator;
use frontend\models\OperatorFavorite;

class ProfileController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionFavorite()
    {
        return $this->render('favorite');
    }

    public function actionSetting()
    {
        $request = Yii::$app->request;
        $editProfileForm = new \frontend\forms\EditProfileForm();
        $editProfileForm->loadData();

        $updateEmailForm = new \frontend\forms\UpdateEmailForm();
        $changePasswordForm = new \frontend\forms\ChangePasswordForm();
        return $this->render('setting', [
            'editProfileForm' => $editProfileForm,
            'updateEmailForm' => $updateEmailForm,
            'changePasswordForm' => $changePasswordForm,
        ]);
    }

	public function actionComplete()
    {
        $request = Yii::$app->request;

        $model = new \frontend\forms\EditProfileForm();
        if ($model->load($request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->loadData();
        }

        // operators 
        $favorites = OperatorFavorite::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->select(['operator_id'])
        ->asArray()
        ->all();
        $operatorFavoriteIds = ArrayHelper::getColumn($favorites, 'operator_id');
        $operators = Operator::find()
        ->where(['NOT IN', 'id', $operatorFavoriteIds])
        ->limit(5)
        ->all();


        return $this->render('complete', [
            'model' => $model,
            'operators' => $operators,
        ]);
    }

    public function actionUpdateAvatar() 
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $user = Yii::$app->user->getIdentity();
        $user->avatar = $request->post('id');
        $user->save();
        return $this->asJson(['status' => true]);
    }

    public function actionUpdateProfile()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\EditProfileForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }

    public function actionUpdateEmail()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\UpdateEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }

    public function actionUpdatePassword()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->change()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }
}