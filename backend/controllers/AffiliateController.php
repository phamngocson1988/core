<?php
namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\User;
use backend\models\UserAffiliate;
use backend\models\UserCommission;
use backend\models\AffiliateCommissionWithdraw;
use backend\forms\FetchAffiliateForm;
use backend\forms\FetchAffiliateCommissionForm;
use backend\forms\FetchCommissionWithdrawForm;
use backend\behaviors\UserAffiliateBehavior;
use backend\behaviors\UserCommissionBehavior;

use backend\models\Affiliate;

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
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $request = Yii::$app->request;
        $form = new FetchAffiliateForm([
            'user_id' => $request->get('user_id'), 
            'status' => UserAffiliate::STATUS_ENABLE,
            'report_start_date' => $request->get('report_start_date'),
            'report_end_date' => $request->get('report_end_date'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form
        ]);
    }

    public function actionView($id)
    {
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $affiliate = UserAffiliate::find()->where(['user_id' => $id])->with('user')->one();
        $user = $affiliate->user;
        $user->attachBehavior('affiliate', UserAffiliateBehavior::className());
        $user->attachBehavior('commission', UserCommissionBehavior::className());

        $request = Yii::$app->request;
        $form = new FetchAffiliateCommissionForm([
            'user_id' => $id, 
            'member_id' => $request->get('member_id'), 
            'report_start_date' => $request->get('report_start_date'),
            'report_end_date' => $request->get('report_end_date'),
        ]);
        $command = $form->getCommand()->groupBy('member_id');
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('view', [
            'affiliate' => $affiliate,
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'user' => $user,
        ]);
    }

    public function actionRequest()
    {
        $this->view->params['main_menu_active'] = 'affiliate.request';
        $command = Affiliate::find()->where(['status' => Affiliate::STATUS_DISABLE]);
        $command->with('user');
        $command->orderBy(['created_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('request', [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionUpgrade($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $userAffiliate = Affiliate::findOne($id);
            if (!$userAffiliate) throw new NotFoundHttpException('Not found');
            $userAffiliate->generateAffiliateCode();
            $userAffiliate->status = Affiliate::STATUS_ENABLE;
            return $this->asJson(['status' => $userAffiliate->save()]);
        }
    }

    public function actionDowngrade($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $userAffiliate = Affiliate::findOne($id);
            if (!$userAffiliate) throw new NotFoundHttpException('Not found');
            $userAffiliate->delete();
            // Send mail notification
            $admin = Yii::$app->params['email_admin'];
            $siteName = Yii::$app->name;
            Yii::$app->mailer->compose('cancel_affiliate_request', [
             'affiliate' => $userAffiliate
            ])
            ->setTo($userAffiliate->user->email)
            ->setFrom([$admin => $siteName])
            ->setSubject("KINGGEMS.US - Your affiliate request is cancelled")
            ->setTextBody("Your affiliate request is cancelled")
            ->send();
            return $this->asJson(['status' => true]);
        }
    }

    // Withdraw
    public function actionWithdraw()
    {
        $this->view->params['main_menu_active'] = 'affiliate.withdraw';
        $request = Yii::$app->request;
        $form = new FetchCommissionWithdrawForm([
            'user_id' => $request->get('user_id'), 
            'status' => $request->get('status'), 
            'created_start_date' => $request->get('created_start_date'),
            'created_end_date' => $request->get('created_end_date'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('withdraw', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionCompleted($user_id)
    {
        $this->view->params['main_menu_active'] = 'affiliate.withdraw';
        $request = Yii::$app->request;
        $form = new FetchCommissionWithdrawForm([
            'user_id' => $request->get('user_id'), 
            'status' => AffiliateCommissionWithdraw::STATUS_EXECUTED, 
            'created_start_date' => $request->get('created_start_date'),
            'created_end_date' => $request->get('created_end_date'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('completed', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionMember($member_id) 
    {
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $request = Yii::$app->request;
        $form = new FetchAffiliateCommissionForm([
            'id' => $request->get('id'), 
            'member_id' => $member_id, 
            'report_start_date' => $request->get('report_start_date'),
            'report_end_date' => $request->get('report_end_date'),
            'status' => $request->get('status'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('member', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionDeleteCommission($id)
    {
        $request = Yii::$app->request;
        $model = UserCommission::findOne($id);
        if ($model) {
            $model->status = UserCommission::STATUS_WITHDRAWED;
            $model->save();
            return $this->renderJson(true);
        } else {
            $message = ($model) ? reset($form->getErrorSummary(false)) : "Record #$id not found";
            return $this->renderJson(false, [], $message);
        }
    }

    public function actionReadyCommission($id)
    {
        $request = Yii::$app->request;
        $model = UserCommission::findOne($id);
        if ($model) {
            $model->valid_from_date = date('Y-m-d');
            $model->save();
            return $this->renderJson(true);
        } else {
            $message = ($model) ? reset($form->getErrorSummary(false)) : "Record #$id not found";
            return $this->renderJson(false, [], $message);
        }
    }

    public function actionExecuteWithdraw($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\ExecuteAffiliateCommissionWithdrawForm([
            'id' => $id,
            'action' => $request->get('action'),
            'note' => $request->post('note')
        ]);
        return $this->asJson(['status' => $model->run()]);
    }
}