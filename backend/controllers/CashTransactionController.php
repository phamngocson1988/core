<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use backend\models\CashTransaction;

class CashTransactionController extends Controller
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
        $this->view->params['main_menu_active'] = 'cashtransaction.index';
        $request = Yii::$app->request;
        $form = new \backend\forms\FetchCashTransactionForm([
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

    public function actionTopupRoot($id)
    {
        $this->view->params['main_menu_active'] = 'cashtransaction.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\TopupRootCashAccountTransactionForm([
            'bank_account_id' => $id,
            'status' => $request->post('status')
        ]);
        $account = $model->getBankAccount();
        if (!$account) throw new NotFoundHttpException("Không tìm thấy quỹ tiền mặt");

        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', 'Bạn vừa nạp tiền vào quỹ thành công.');
                return $this->redirect(Url::to(['cash-transaction/index']));
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                Yii::$app->session->setFlash('error', $error);
            }
        }

        return $this->render('topup-root', [
            'model' => $model,
            'account' => $account
        ]);
    }

    public function actionReturnAllToRoot($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\ReturnCashAccountTransactionForm([
            'bank_account_id' => $id,
            'status' => CashTransaction::STATUS_COMPLETED
        ]);

        if ($request->isPost && $request->isAjax) {
            if ($model->validate() && $model->create()) {
                return $this->asJson(['status' => true]);
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'errors' => $error]);
            }
        }
        throw new NotFoundHttpException("Không tìm thấy trang");
    }

    public function actionReturnApartToRoot($id)
    {
        $this->view->params['main_menu_active'] = 'cashtransaction.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\ReturnCashAccountTransactionForm([
            'bank_account_id' => $id,
            'status' => $request->post('status')
        ]);
        $account = $model->getBankAccount();
        if (!$account) throw new NotFoundHttpException("Không tìm thấy tài khoản tiền mặt của nhân viên này");
        $root = $model->getRootAccount();
        if (!$root) throw new NotFoundHttpException("Không tìm thấy quỹ tiền mặt");

        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', 'Bạn vừa nạp tiền vào quỹ thành công.');
                return $this->redirect(Url::to(['cash-transaction/index']));
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                Yii::$app->session->setFlash('error', $error);
            }
        }

        return $this->render('return', [
            'model' => $model,
            'account' => $account,
            'root' => $root,
            'account_amount' => $model->getAccountAmount()
        ]);
    }

    public function actionTopup($id)
    {
        $this->view->params['main_menu_active'] = 'cashtransaction.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\TopupCashAccountTransactionForm([
            'bank_account_id' => $id,
            'status' => $request->post('status')
        ]);
        $account = $model->getBankAccount();
        if (!$account) throw new NotFoundHttpException("Không tìm thấy tài khoản tiền mặt của nhân viên này");
        $root = $model->getRootAccount();
        if (!$root) throw new NotFoundHttpException("Không tìm thấy quỹ tiền mặt");
        $root_amount = $model->getRootAmount();

        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', 'Bạn vừa nạp tiền vào quỹ thành công.');
                return $this->redirect(Url::to(['cash-transaction/index']));
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                Yii::$app->session->setFlash('error', $error);
            }
        }

        return $this->render('topup', [
            'model' => $model,
            'account' => $account,
            'root' => $root,
            'root_amount' => $root_amount
        ]);
    }


    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\DeleteCashTransactionForm([
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
        $model = new \backend\forms\CompleteCashTransactionForm([
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
}
