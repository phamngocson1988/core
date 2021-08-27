<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use backend\forms\FetchTransactionForm;
use backend\models\PaymentTransaction;
use backend\models\UserWallet;
use backend\behaviors\PaymentTransactionMailBehavior;

class PaymentTransactionController extends Controller
{
    /**
     * @inheritdoc
     */
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
        $this->view->params['main_menu_active'] = 'transaction.index';
        $request = Yii::$app->request;
        $data = [
            'created_at_from' => $request->get('created_at_from'),
            'created_at_to' => $request->get('created_at_to'),
            'id' => $request->get('id'),
            'user_id' => $request->get('user_id'),
            'payment_method' => $request->get('payment_method'),
            'status' => $request->get('status', [PaymentTransaction::STATUS_COMPLETED, PaymentTransaction::STATUS_PENDING]),
        ];
        $search = new FetchTransactionForm($data);
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('index', [
            'search' => $search,
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionTrash()
    {
        $this->view->params['main_menu_active'] = 'transaction.trash';
        $request = Yii::$app->request;
        $data = [
            'created_at_from' => $request->get('created_at_from'),
            'created_at_to' => $request->get('created_at_to'),
            'id' => $request->get('id'),
            'remark' => $request->get('remark'),
            'user_id' => $request->get('user_id'),
            'status' => PaymentTransaction::STATUS_DELETED,
        ];
        $search = new FetchTransactionForm($data);
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('trash', [
            'search' => $search,
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionMoveToTrash($id)
    {
        $request = Yii::$app->request;
        $transaction = PaymentTransaction::findOne($id);
        if (!$transaction) return $this->asJson(['status' => false, 'errors' => 'Không tim thấy giao dịch']);
        if ($transaction->isCompleted()) return $this->asJson(['status' => false, 'errors' => 'Không thể xóa giao dịch']);
        $transaction->status = PaymentTransaction::STATUS_DELETED;
        if ($transaction->save(false, ['status'])) {
            return $this->asJson(['status' => true]);
        } else {
            $errors = $transaction->getErrorSummary(true);
            return $this->asJson(['status' => false, 'errors' => reset($errors)]);
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $transaction = PaymentTransaction::findOne($id);
        if (!$transaction) return $this->asJson(['status' => false, 'errors' => 'Không tim thấy giao dịch']);
        if (!$transaction->isDeleted()) return $this->asJson(['status' => false, 'errors' => 'Không thể xóa giao dịch']);
        if ($transaction->delete()) {
            return $this->asJson(['status' => true]);
        } else {
            $errors = $transaction->getErrorSummary(true);
            return $this->asJson(['status' => false, 'errors' => reset($errors)]);
        }
    }

    public function actionSendMailOfflinePayment($id)
    {
        $model = PaymentTransaction::findOne($id);
        if ($model) {
            $model->attachBehavior('mail', PaymentTransactionMailBehavior::className());
            $model->send('admin_notify_offline_payment', '[KINGGEMS]-FAILED TRANSACTION');
        }
        return $this->renderJson(true);
    }
}
