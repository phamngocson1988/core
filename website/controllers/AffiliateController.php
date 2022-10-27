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
use website\models\Affiliate;
use website\models\AffiliateCommissionWithdraw;
// forms
use website\forms\RegisterAffiliateForm;

class AffiliateController extends Controller
{
    public function behaviors()
    {
        return [
            'blockip' => [
                'class' => \website\components\filters\BlockIpAccessControl::className(),
            ],
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
        $request = Yii::$app->request;

        if (!$user->isAffiliate()) {
            return $this->redirect(['affiliate/register']);
        }

        $addAccountForm = new \website\forms\CreateAffiliateAccountForm([
            'user_id' => Yii::$app->user->id,
        ]);
        $withdrawForm = new \website\forms\SendAffiliateWithdrawRequestForm([
            'user_id' => Yii::$app->user->id,
        ]);

        $withdrawCommand = AffiliateCommissionWithdraw::find()->where([
            'user_id' => Yii::$app->user->id
        ])->limit(10)->orderBy(['id' => SORT_DESC]);
        $withdraws = $withdrawCommand->all();
        $withdrawTotalAmount = $withdrawCommand->sum('amount');

        // commissions
        $affiliateCommissionForm = new \website\forms\FetchAffiliateCommissionForm([
            'user_id' => Yii::$app->user->id,
            'status' => $request->get('status'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ]);
        $affiliateCommissionCommand = $affiliateCommissionForm->getCommand();
        $pages = new Pagination(['totalCount' => $affiliateCommissionCommand->count()]);
        $commissions = $affiliateCommissionCommand->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', [
            'addAccountForm' => $addAccountForm,
            'withdrawForm' => $withdrawForm,
            'withdraws' => $withdraws,
            'withdrawTotalAmount' => $withdrawTotalAmount,
            'commissions' => $commissions,
            'pages' => $pages,
            'searchCommission' => $affiliateCommissionForm,
        ]);
    }

    public function actionRegister()
    {
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        if ($user->isAffiliate()) {
            return $this->redirect(['affiliate/index']);
        }
        $model = new RegisterAffiliateForm(['user_id' => Yii::$app->user->id]);
        $isRegisterSuccess = false;
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->register()) {
                $isRegisterSuccess = true;
            } else {
                $message = $model->getErrorSummary(true);
                $message = reset($message);
                Yii::$app->session->setFlash('error', $message);
            }
        }
        
        return $this->render('register',[
            'model' => $model,
            'sentRequest' => $user->hasPendingAffiliateRequest(),
            'isRegisterSuccess' => (int)$isRegisterSuccess
        ]);
    }

    public function actionCancel()
    {
        $aff = Affiliate::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->andWhere(['status' => Affiliate::STATUS_DISABLE])
        ->one();
        if ($aff) $aff->delete();
        return $this->redirect(['affiliate/register']);
    }

    public function actionAddAccount()
    {
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        $model = new \website\forms\CreateAffiliateAccountForm(['user_id' => Yii::$app->user->id]);
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->create()) {
                return $this->asJson(['status' => true, 'data' => ['message' => 'You have added new account successfullly.']]);
            } else {
                $message = $model->getErrorSummary(true);
                $message = reset($message);
                return $this->asJson(['status' => false, 'errors' => $message]);
            }
        }
    }

    public function actionDeleteAccount($id)
    {
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        $model = new \website\forms\DeleteAffiliateAccountForm([
            'user_id' => Yii::$app->user->id,
            'id' => $id,
        ]);
        if ($request->isPost) {
            if ($model->validate() && $model->delete()) {
                return $this->asJson(['status' => true, 'data' => ['message' => 'You have deleted account successfullly.']]);
            } else {
                $message = $model->getErrorSummary(true);
                $message = reset($message);
                Yii::$app->session->setFlash('error', $message);
                return $this->asJson(['status' => false, 'errors' => $message]);
            }
        }
    }

    public function actionSendWithdrawRequest()
    {
        $this->view->params['main_menu_active'] = 'affiliate.index';
        $request = Yii::$app->request;
        $user = Yii::$app->user->getIdentity();
        $model = new \website\forms\SendAffiliateWithdrawRequestForm([
            'user_id' => Yii::$app->user->id,
        ]);
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->create()) {
                return $this->asJson(['status' => true, 'data' => ['message' => 'You have sent withdraw request successfullly.']]);
            } else {
                $message = $model->getErrorSummary(true);
                $message = reset($message);
                return $this->asJson(['status' => false, 'errors' => $message]);
            }
        }
    }

}