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
use frontend\models\Complain;
use frontend\models\ComplainFollow;
use frontend\models\UserBadge;


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
        
        return $this->render('index', [
            'badges' => $badges
        ]);
    }

    public function actionBadge()
    {
        $request = Yii::$app->request;
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $command = UserBadge::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->orderBy(['id' => SORT_DESC]);
        $badge = $request->get('badge');
        if ($badge) {
            $command->andWhere(['badge' => $badge]);
        }

        $models = $command->all();
        $total = $command->count();
        $html = $this->renderPartial('badge', ['models' => $models]);
        return $this->asJson(['status' => true, 'data' => [
            'items' => $html,
            'total' => $total
        ]]);
    }

    public function actionFavorite()
    {
        $favorites = OperatorFavorite::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->select('operator_id')->asArray()->all();
        $operatorIds = ArrayHelper::getColumn($favorites, 'operator_id');
        $operators = Operator::findAll($operatorIds);

        $follows = ComplainFollow::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->select('complain_id')->asArray()->all();
        $complainIds = ArrayHelper::getColumn($follows, 'complain_id');
        $complains = Complain::findAll($complainIds);

        return $this->render('favorite', [
            'operators' => $operators,
            'complains' => $complains,
        ]);
    }

    public function actionSetting()
    {
        $request = Yii::$app->request;
        $editProfileForm = new \frontend\forms\EditProfileForm();
        $editProfileForm->loadData();

        $updateEmailForm = new \frontend\forms\UpdateEmailForm();
        $changePasswordForm = new \frontend\forms\ChangePasswordForm();

        $user = Yii::$app->user->getIdentity();
        $settings = $user->getSettings();
        return $this->render('setting', [
            'editProfileForm' => $editProfileForm,
            'updateEmailForm' => $updateEmailForm,
            'changePasswordForm' => $changePasswordForm,
            'settings' => $settings,
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

    public function actionNotification()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $key = $request->post('key');
        $value = $request->post('value');
        $user = Yii::$app->user->getIdentity();
        $user->setSetting($key, $value);
        return $this->asJson(['status' => true, 'key' => $key, 'value' => $value]);
    }
}