<?php
namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
                        'actions' => ['not-be-affiliate', 'send-request', 'cancel-request'],
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
                    else return $this->redirect(['affiliate/not-be-affiliate']);
                }
                
            ],
        ];
    }

    public function actionNotBeAffiliate()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['main_menu_active'] = 'affiliate.index';
        return $this->render('not_be_affiliate');
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
        $command = UserAffiliate::find()->where(['user_id' => $user->id]);
        if ($request->get('status')) {
            switch ($request->get('status')) {
                case 'completed':
                    $command->andWhere(['status' => UserAffiliate::STATUS_COMPLETED]);
                    break;
                case 'pending':
                $command->andWhere(['status' => UserAffiliate::STATUS_PENDING]);
                $command->andWhere(['<', 'date(created_at)' => $readyDate]);
                    break;
                case 'ready':
                    $command->andWhere(['status' => UserAffiliate::STATUS_PENDING]);
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
        $member = User::find()->where(['affiliated_with' => $user->id])->count();
        return $this->render('index', [
            'member' => $member,
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