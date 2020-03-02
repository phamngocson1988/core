<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use backend\forms\FetchTransactionForm;
use backend\models\PaymentTransaction;
use backend\models\UserWallet;
use backend\events\PaymentTransactionEvent;
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
            'payment_type' => $request->get('payment_type'),
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

    public function actionPaypal()
    {
        $this->view->params['main_menu_active'] = 'transaction.paypal';
        $request = Yii::$app->request;
        $data = [
            'created_at_from' => $request->get('created_at_from'),
            'created_at_to' => $request->get('created_at_to'),
            'id' => $request->get('id'),
            'remark' => $request->get('remark'),
            'user_id' => $request->get('user_id'),
            'status' => PaymentTransaction::STATUS_PENDING,
            'payment_method' => 'paypal',
            'payment_type' => 'online',
        ];
        $search = new FetchTransactionForm($data);
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('paypal', [
            'search' => $search,
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionOffline()
    {
        $this->view->params['main_menu_active'] = 'transaction.offline';
        $request = Yii::$app->request;
        $data = [
            'created_at_from' => $request->get('created_at_from'),
            'created_at_to' => $request->get('created_at_to'),
            'id' => $request->get('id'),
            'remark' => $request->get('remark'),
            'user_id' => $request->get('user_id'),
            'payment_type' => 'offline',
            'status' => PaymentTransaction::STATUS_PENDING,
        ];
        $search = new FetchTransactionForm($data);
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('offline', [
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

    public function actionPayOffline($id) 
    {
        $request = Yii::$app->request;
        $transaction = PaymentTransaction::findOne($id);
        $transaction->setScenario(PaymentTransaction::SCENARIO_CONFIRM_OFFLINE_PAYMENT);
        if (!$transaction) return $this->asJson(['status' => false, 'errors' => 'Không tim thấy giao dịch']);
        if ($transaction->status == PaymentTransaction::STATUS_COMPLETED) return $this->asJson(['status' => false, 'errors' => 'Giao dịch đã được thanh toán']);
        $transaction->on(PaymentTransaction::EVENT_AFTER_UPDATE, [PaymentTransactionEvent::className(), 'welcomeBonus']);
        $transaction->on(PaymentTransaction::EVENT_AFTER_UPDATE, [PaymentTransactionEvent::className(), 'topupUserWallet']);
        $transaction->on(PaymentTransaction::EVENT_AFTER_UPDATE, [PaymentTransactionEvent::className(), 'applyReferGift']);
        if ($transaction->load($request->post()) && $transaction->save()) {
            return $this->asJson(['status' => true]);
        } else {
            $errors = $transaction->getErrorSummary(true);
            return $this->asJson(['status' => false, 'errors' => reset($errors)]);
        }
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
