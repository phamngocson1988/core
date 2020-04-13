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
                        'roles' => ['manager'],
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
}
