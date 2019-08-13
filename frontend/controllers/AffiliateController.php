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
use frontend\models\UserCommissionWithdraw;
use frontend\models\UserAffiliate;
use frontend\models\User;
use frontend\behaviors\UserCommissionBehavior;

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
                        'actions' => ['register', 'cancel-request'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $user = Yii::$app->user->identity;
                            $aff = $user->affiliate;
                            if (!$aff) return false;
                            if (!$aff->isEnable()) return false;
                            return true;
                        },

                    ],

                ],
                'denyCallback' => function ($rule, $action) {
                    $user = Yii::$app->user->identity;
                    $aff = $user->affiliate;
                    if (Yii::$app->user->getIsGuest()) Yii::$app->user->loginRequired();
                    elseif (!$aff) return $this->redirect(['affiliate/register']);
                    elseif (!$aff->isEnable()) return $this->redirect(['affiliate/register']);
                    else return ;
                }
                
            ],
        ];
    }

    public function actionRegister()
    {
        $user = Yii::$app->user->identity;
        
        $request = Yii::$app->request;
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';

        $model = UserAffiliate::findOne($user->id);
        $sent = false;
        if ($model) {
            if ($model->isEnable()) return $this->redirect(['affiliate/index']);
            $sent = true;
        } else {
            $model = new UserAffiliate(['user_id' => $user->id]);
        }
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Your request is sent to administrators');
            $sent = true;
        }
        return $this->render('register', ['model' => $model, 'sent' => $sent]);
    }

    public function actionCancelRequest()
    {
        $user = Yii::$app->user->identity;
        $aff = UserAffiliate::findOne($user->id);
        if ($aff) $aff->delete();
        return $this->asJson(['status' => true]);
    }

    public function actionIndex()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $user = Yii::$app->user->getIdentity();
        $user->attachBehavior('UserCommissionBehavior', UserCommissionBehavior::className(), ['id' => $user->id]);
        $request = Yii::$app->request;
        $status = $request->get('status');
        switch ($status) {
            case 'pending':
                $command = $user->getPendingCommission();
                break;
            case 'ready':
                $command = $user->getReadyCommission();
                break;
            default :
                $command = $user->getCommission();
                break;
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
            'status' => $status,
            'can_withdraw' => $user->canWithDraw(),
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

    public function actionWithdraw()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $user = Yii::$app->user->getIdentity();
        $command = UserCommissionWithdraw::find()->where(['user_id' =>$user->id]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();
        $member = $user->getAffiliateChildren()->count();
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