<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use backend\models\CashTransaction;

class CashAccountController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'cashaccount.index';
        $request = Yii::$app->request;
        $data = ['bank_id' => $request->get('bank_id')];
        $sessionUser = Yii::$app->user;
        if (!$sessionUser->can('manager')) {
            $data['account_number'] = $sessionUser->id;
        }
        $form = new \backend\forms\FetchCashAccountForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        $bankAccountIds = ArrayHelper::getColumn($models, 'id');
        $transactionForm = new \backend\forms\FetchCashTransactionForm([
            'status' => CashTransaction::STATUS_COMPLETED
        ]);
        $transactionCommand = $transactionForm->getCommand();
        $transactionCommand->andWhere(["IN", "bank_account_id", $bankAccountIds]);
        $report = $transactionCommand->groupBy(['bank_account_id'])->select(['bank_account_id', 'SUM(amount) as amount'])->asArray()->all();
        $report = ArrayHelper::map($report, 'bank_account_id', 'amount');

        return $this->render('index', [
            'models' => $models,
            'search' => $form,
            'pages' => $pages,
            'report' => $report
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'cashaccount.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateCashAccountForm();
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', 'Bạn vừa tạo mới quỹ tiền mặt cho nhân viên.');
                return $this->redirect(Url::to(['cash-account/index']));
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                Yii::$app->session->setFlash('error', $error);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
