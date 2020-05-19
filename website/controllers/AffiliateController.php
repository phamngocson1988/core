<?php
namespace website\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
// models
use website\models\UserAffiliate;
// forms
use website\forms\RegisterUserAffiliateForm;
// use website\models\UserCommissionWithdraw;
// use website\models\User;
// use website\behaviors\UserCommissionBehavior;
use website\behaviors\UserAffiliateBehavior;

class AffiliateController extends Controller
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
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $user = Yii::$app->user->getIdentity();
        $user->attachBehavior('affiliate', UserAffiliateBehavior::className());
        if (!$user->isAffiliate()) {
            return $this->redirect(['affiliate/register']);
        }
        return $this->render('index');
    }

    public function actionRegister()
    {
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        $user->attachBehavior('affiliate', UserAffiliateBehavior::className());
        if ($user->isAffiliate()) {
            return $this->redirect(['affiliate/index']);
        }
        $model = new RegisterUserAffiliateForm(['user_id' => Yii::$app->user->id]);
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->register()) {
                Yii::$app->session->setFlash('success', 'Your request is sent to administrators');
            } else {
                $message = $model->getErrorSummary(true);
                $message = reset($message);
                Yii::$app->session->setFlash('error', $message);
            }
        }
        
        return $this->render('register',[
            'model' => $model,
            'sentRequest' => $user->hasPendingAffiliateRequest(),
        ]);
    }

    public function actionCancel()
    {
        $aff = UserAffiliate::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->andWhere(['status' => UserAffiliate::STATUS_DISABLE])
        ->one();
        if ($aff) $aff->delete();
        return $this->redirect(['affiliate/register']);
    }

    

    public function actionMember()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $user = Yii::$app->user->getIdentity();
        $user->attachBehavior('affiliate', UserAffiliateBehavior::className());
        $request = Yii::$app->request;
        $command = $user->getAffiliateMembers();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();
        return $this->render('member', [
            'member' => $command->count(),
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionWithdraw()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $user = Yii::$app->user->getIdentity();
        $user->attachBehavior('affiliate', UserAffiliateBehavior::className());
        $command = UserCommissionWithdraw::find()->where(['user_id' =>$user->id]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();
        $member = $user->getAffiliateMembers()->count();
        return $this->render('withdraw', [
            'member' => $member,
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionWithdrawRequest()
    {
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';

        $model = new UserCommissionWithdraw();
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Your request is sent to administrators');
            return $this->redirect(['affiliate/withdraw']);
        }
        return $this->render('withdraw_request', [
            'model' => $model,
            'user' => $user,
        ]);
    }

}