<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use backend\models\CashTransaction;

class CashController extends Controller
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
        $this->view->params['main_menu_active'] = 'cash.index';
        $request = Yii::$app->request;
        $form = new \backend\forms\FetchCashForm([
            'currency' => $request->get('currency'),
        ]);
        $command = $form->getCommand();
        $models = $command->all();

        $transactionForm = new \backend\forms\FetchCashTransactionForm([
            'status' => CashTransaction::STATUS_COMPLETED
        ]);
        $transactionCommand = $transactionForm->getCommand();
        $report = $transactionCommand->groupBy(['currency'])->select(['currency', 'SUM(amount) as amount'])->asArray()->all();
        $report = ArrayHelper::map($report, 'currency', 'amount');
        return $this->render('index', [
            'models' => $models,
            'search' => $form,
            'report' => $report
        ]);
    }

    public function actionCreate($currency)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateCashForm([
            'currency' => $currency,
        ]);

        if ($request->isAjax) {
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

    // public function actionEdit($id)
    // {
    //     $this->view->params['main_menu_active'] = 'cash.index';
    //     $request = Yii::$app->request;
    //     $model = \backend\forms\EditBankForm::findOne($id);
    //     if (!$model) throw new NotFoundHttpException("Không tìm thấy dữ liệu");
        
    //     if ($request->isPost) {
    //         if ($model->load($request->post()) && $model->validate() && $model->save()) {
    //             Yii::$app->session->setFlash('success', 'Bạn vừa chỉnh sửa ngân hàng thành công.');
    //             return $this->redirect(Url::to(['bank/index']));
    //         } else {
    //             Yii::$app->session->setFlash('error', $model->getErrors());
    //         }
    //     }
    //     return $this->render('edit', [
    //         'model' => $model,
    //     ]);
    // }
}
