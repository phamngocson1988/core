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
            'status' => $request->get('status'),
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
            'status' => 'pending',
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

    public function actionPayOffline($id) 
    {
        $request = Yii::$app->request;
        $transaction = PaymentTransaction::findOne($id);
        $transaction->setScenario(PaymentTransaction::SCENARIO_CONFIRM_OFFLINE_PAYMENT);
        if (!$transaction) return $this->asJson(['status' => false, 'errors' => 'Không tim thấy giao dịch']);
        if ($transaction->status == PaymentTransaction::STATUS_COMPLETED) return $this->asJson(['status' => false, 'errors' => 'Giao dịch đã được thanh toán']);
        $transaction->on(PaymentTransaction::EVENT_AFTER_UPDATE, [PaymentTransactionEvent::className(), 'welcomeBonus']);
        if ($transaction->load($request->post()) && $transaction->save()) {
            $user = $transaction->user;
            $wallet = new UserWallet();
            $wallet->coin = $transaction->total_coin;
            $wallet->balance = $user->getWalletAmount() + $wallet->coin;
            $wallet->type = UserWallet::TYPE_INPUT;
            $wallet->description = "Transaction #$transaction->id";
            $wallet->ref_name = PaymentTransaction::className();
            $wallet->ref_key = $transaction->auth_key;
            $wallet->created_by = Yii::$app->user->id;
            $wallet->user_id = $user->id;
            $wallet->status = UserWallet::STATUS_COMPLETED;
            $wallet->payment_at = date('Y-m-d H:i:s');
            $wallet->save();
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
        if ($transaction->isCompleted()) return $this->asJson(['status' => false, 'errors' => 'Không thể xóa giao dịch']);
        if ($transaction->delete()) return $this->asJson(['status' => true]);
        else {
            $errors = $transaction->getErrorSummary(true);
            return $this->asJson(['status' => false, 'errors' => reset($errors)]);
        }
    }
}
