<?php
namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use frontend\models\UserCommission;
use frontend\models\UserAffiliate;
use frontend\models\User;
use frontend\forms\TakeCommission;

/**
 * AffiliateController
 */
class AffiliateController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['not-be-affiliate', 'register', 'send-request', 'cancel-request'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->affiliate_code;
                        },

                    ],

                ],
                'denyCallback' => function ($rule, $action) {
                    $user = Yii::$app->user;
                    if ($user->getIsGuest()) $user->loginRequired();
                    else return $this->redirect(['affiliate/register']);
                }
                
            ],
        ];
    }

    public function actionNotBeAffiliate()
    {
        $user = Yii::$app->user->identity;
        if ($user->affiliate_code) return $this->redirect(['affiliate/index']);
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';
        return $this->render('not_be_affiliate');
    }

    public function actionRegister()
    {
        $user = Yii::$app->user->identity;
        if ($user->affiliate_code) return $this->redirect(['affiliate/index']);
        $request = Yii::$app->request;
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';

        $model = UserAffiliate::findOne($user->id);
        $sent = ($model) ? true : false;
        if (!$model) $model = new UserAffiliate(['user_id' => $user->id]);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Your request is sent to administrators');
            $sent = true;
        }
        return $this->render('register', ['model' => $model, 'sent' => $sent]);
    }

    public function actionSendRequest()
    {
        $user = Yii::$app->user->identity;
        $user->affiliate_request = 1;
        $user->affiliate_request_time = date('Y-m-d H:i:s');
        $user->save(true, ['affiliate_request', 'affiliate_request_time']);
        return $this->asJson(['status' => true]);
    }

    public function actionCancelRequest()
    {
        $user = Yii::$app->user->identity;
        $user->affiliate_request = 0;
        $user->affiliate_request_time = null;
        $user->save(true, ['affiliate_request', 'affiliate_request_time']);
        $aff = UserAffiliate::findOne($user->id);
        if ($aff) $aff->delete();
        return $this->asJson(['status' => true]);
    }

    public function actionIndex()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $user = Yii::$app->user->getIdentity();
        $request = Yii::$app->request;
        $duration = Yii::$app->settings->get('AffiliateProgramForm', 'duration', 30);
        $readyDate = date('Y-m-d', strtotime(sprintf("-%d days", $duration)));
        $command = UserCommission::find()->where(['user_id' => $user->id]);
        if ($request->get('status')) {
            switch ($request->get('status')) {
                case 'completed':
                    $command->andWhere(['status' => UserCommission::STATUS_COMPLETED]);
                    break;
                case 'pending':
                $command->andWhere(['status' => UserCommission::STATUS_PENDING]);
                $command->andWhere(['<', 'date(created_at)' => $readyDate]);
                    break;
                case 'ready':
                    $command->andWhere(['status' => UserCommission::STATUS_PENDING]);
                    $command->andWhere(['>=', 'date(created_at)' => $readyDate]);
                break;
            }
            
        }
        if ($request->get('created_at')) {
            $command->andWhere(['date(created_at)' => $request->get('created_at')]);
        }
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        $member = $user->getAffiliateChildren()->count();
        return $this->render('index', [
            'member' => $member,
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionMember()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $user = Yii::$app->user->getIdentity();
        $request = Yii::$app->request;
        $command = $user->getAffiliateChildren();
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

    public function actionTake($id)
    {
        $commission = TakeCommission::findOne($id);
        return $this->asJson(['status' => $commission->takeCommission(), 'errors' => $commission->getErrorSummary(true)]);
    }

}