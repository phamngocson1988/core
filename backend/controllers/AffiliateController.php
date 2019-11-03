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
use backend\models\UserCommissionWithdraw;
use backend\forms\FetchAffiliateForm;
use backend\forms\FetchAffiliateCommissionForm;
use backend\forms\FetchCommissionWithdrawForm;

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
        ]);
    }

    public function actionRequest()
    {
        $this->view->params['main_menu_active'] = 'affiliate.request';
        $command = UserAffiliate::find()->where(['status' => UserAffiliate::STATUS_DISABLE]);
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
            $userAffiliate = UserAffiliate::findOne($id);
            if (!$userAffiliate) throw new NotFoundHttpException('Not found');
            $userAffiliate->generateAffiliateCode();
            $userAffiliate->status = UserAffiliate::STATUS_ENABLE;
            return $this->asJson(['status' => $userAffiliate->save()]);
        }
    }

    public function actionDowngrade($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $userAffiliate = UserAffiliate::findOne($id);
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
            'status' => UserCommissionWithdraw::STATUS_EXECUTED, 
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
            'member_id' => $member_id, 
            'report_start_date' => $request->get('report_start_date'),
            'report_end_date' => $request->get('report_end_date'),
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

    public function actionExecuteWithdraw($id)
    {
        $request = Yii::$app->request;
        $action = $request->get('action');
        $model = UserCommissionWithdraw::findOne($id);
        if (!$model) throw new NotFoundHttpException("Not found", 1);
        
        switch ($action) {
            case 'approve':
                return $this->asJson(['status' => $model->approve()]);
                break;
            case 'disapprove':
                return $this->asJson(['status' => $model->disapprove()]);
                break;
            case 'execute':
                $model->note = $request->post('note');
                return $this->asJson(['status' => $model->execute()]);
                break;
            default:
                # code...
                break;
        }
    }
}