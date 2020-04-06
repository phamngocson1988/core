<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use backend\models\BankAccount;

class BankTransactionController extends Controller
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
        $this->view->params['main_menu_active'] = 'banktransaction.index';
        $request = Yii::$app->request;
        $form = new \backend\forms\FetchBankTransactionForm([
            'from_date' => $request->get('from_date'),
            'to_date' => $request->get('to_date'),
            'bank_id' => $request->get('bank_id'),
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

    public function actionCreateInput()
    {
        $this->view->params['main_menu_active'] = 'banktransaction.createinput';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateBankInputTransactionForm([
            'status' => $request->post('status')
        ]);

        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', 'Bạn vừa tạo mới giao dịch thành công.');
                return $this->redirect(Url::to(['bank-transaction/index']));
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                Yii::$app->session->setFlash('error', $error);
            }
        }

        $bankAccounts = BankAccount::find()->all();
        $bankAccounts = ArrayHelper::map($bankAccounts, 'id', function($account, $default) {
            $bank = $account->bank;
            return sprintf("%s - %s - %s - %s", $account->account_name, $account->account_number, $bank->name, $bank->currency);
        });
        return $this->render('create-input', [
            'model' => $model,
            'bankAccounts' => $bankAccounts
        ]);
    }

    public function actionCreateOutput()
    {
        $this->view->params['main_menu_active'] = 'banktransaction.createoutput';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateBankOutputTransactionForm([
            'status' => $request->post('status')
        ]);

        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', 'Bạn vừa tạo mới giao dịch thành công.');
                return $this->redirect(Url::to(['bank-transaction/index']));
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                Yii::$app->session->setFlash('error', $error);
            }
        }

        $bankAccounts = BankAccount::find()->all();
        $bankAccounts = ArrayHelper::map($bankAccounts, 'id', function($account, $default) {
            $bank = $account->bank;
            return sprintf("%s - %s - %s - %s", $account->account_name, $account->account_number, $bank->name, $bank->currency);
        });
        return $this->render('create-output', [
            'model' => $model,
            'bankAccounts' => $bankAccounts
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\DeleteBankTransactionForm([
            'id' => $id
        ]);

        if ($request->isPost && $request->isAjax) {
            if ($model->validate() && $model->delete()) {
                return $this->asJson(['status' => true]);
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'errors' => $error]);
            }
        }
        throw new NotFoundHttpException("Không tìm thấy trang");
    }

    public function actionComplete($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\CompleteBankTransactionForm([
            'id' => $id
        ]);

        if ($request->isPost && $request->isAjax) {
            if ($model->validate() && $model->complete()) {
                return $this->asJson(['status' => true]);
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'errors' => $error]);
            }
        }
        throw new NotFoundHttpException("Không tìm thấy trang");
    }

    public function actionReportByBank()
    {
        $this->view->params['main_menu_active'] = 'banktransaction.reportbank';
        $request = Yii::$app->request;
        $mode = $request->get('mode');
        $form = new \backend\forms\ReportBankTransactionForm([
            'from_date' => $request->get('from_date'),
            'to_date' => $request->get('to_date'),
            'bank_id' => $request->get('bank_id'),
        ]);
        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'thong-ke-giao-dich-theo-ngan-hang.xls';
            return $form->export($fileName);
        }
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('report-bank', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form
        ]);
    }
}
