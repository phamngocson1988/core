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
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->affiliate_code;
                        }
                    ],
                ],
                
            ],
        ];
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

    public function actionRequest()
    {
        $user = Yii::$app->user->getIdentity();
        if ($user->affiliate_code) return $this->redirect(['affiliate/index']);

    }

    public function actionTake($id)
    {
        $commission = TakeCommission::findOne($id);
        return $this->asJson(['status' => $commission->takeCommission(), 'errors' => $commission->getErrorSummary(true)]);
    }

}