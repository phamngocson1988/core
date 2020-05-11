<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use common\components\Controller;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use frontend\forms\EditUserForm;
use frontend\forms\ChangePasswordForm;
use frontend\forms\FetchHistoryOrderForm;
use frontend\forms\FetchHistoryTransactionForm;
use frontend\forms\FetchHistoryWalletForm;
use frontend\forms\CompleteOrderForm;
use frontend\models\Order;
use frontend\models\UserWallet;
use common\models\PaymentTransaction;
use common\models\OrderComplains;
use frontend\behaviors\OrderLogBehavior;

// Notification
use frontend\components\notifications\OrderNotification;
/**
 * UserController
 */
class UserController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                // 'only' => ['index', 'profile', 'password', 'orders', 'order-detail', 'evidence', 'transaction', 'wallet', 'detail'],
                'rules' => [
                    [
                        // 'actions' => ['index', 'profile', 'password', 'orders', 'order-detail', 'evidence', 'transaction', 'wallet', 'detail', 'confirm'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'confirm' => ['post'],
            //         'like' => ['post'],
            //         'dislike' => ['post'],
            //     ],
            // ],
        ];
    }

    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $user = Yii::$app->user->getIdentity();
        $order = Order::find()->where(['customer_id' => $userId])->orderBy(['id' => SORT_DESC])->one();
        $wallet = UserWallet::find()->where(['user_id' => $userId])->orderBy(['id' => SORT_DESC])->one();
        $transaction = PaymentTransaction::find()->where(['user_id' => $userId])->orderBy(['id' => SORT_DESC])->one();
    	return $this->render('index', [
            'order' => $order,
            'wallet' => $wallet,
            'coin' => $user->getWalletAmount(),
            'transaction' => $transaction
        ]);
    }

    public function actionProfile()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['user_menu_active'] = 'user.profile';
    	$request = Yii::$app->request;
    	$model = EditUserForm::findOne(Yii::$app->user->id);
    	if ($request->isPost) {
    		if ($model->load($request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'You have updated successfully.');
    		} else {
                Yii::$app->session->setFlash('error', 'There are something wrong!');
    		}
    		unset($_POST);
    	}
    	return $this->render('profile', ['model' => $model]);
    }

    public function actionPassword()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['user_menu_active'] = 'user.password';
        $request = Yii::$app->request;
        $post = $request->post();
        $model = new ChangePasswordForm();
        
        if ($model->load($post) && $model->change()) {
            $model = new ChangePasswordForm();
            Yii::$app->session->setFlash('success', 'You have updated successfully.');
        } else {
            Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
        }

        return $this->render('password', [
            'model' => $model,
            'user' => Yii::$app->user->getIdentity(),
        ]);
    }

    public function actionOrders()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['user_menu_active'] = 'user.order';
        $request = Yii::$app->request;
        $filter = [
            'customer_id' => Yii::$app->user->id,
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'game_id' => $request->get('game_id'),
            'status' => $request->get('status')
        ];
        $form = new FetchHistoryOrderForm($filter);

        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('orders', [
            'models' => $models,
            'pages' => $pages,
            'filterForm' => $form,
        ]);

    	return $this->render('orders');
    }

    public function actionDetail($id)
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['user_menu_active'] = 'user.order';
        $order = Order::findOne($id);
        if (!$order) throw new NotFoundHttpException("The order not found", 1);
        if ($order->customer_id != Yii::$app->user->id) throw new NotFoundHttpException("The order not found", 1);
        $complainModel = new OrderComplains();
        $complains = OrderComplains::find()->where(['order_id' => $id])->all();
        return $this->render('detail', [
            'model' => $order,
            'complainModel' => $complainModel,
            'complains' => $complains
        ]);
    }

    public function actionConfirm($key)
    {
        $model = new CompleteOrderForm([
            'auth_key' => $key,
            'user_id' => Yii::$app->user->id,
        ]);
        if ($model->save()) {
            return $this->asJson(['status' => true]);
        } else {
            return $this->asJson(['status' => false, 'error' => $model->getErrorSummary(true)]);
        }
    }

    public function actionLike($id)
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        if (!$order) return $this->renderJson(false, [], ['error' => 'Order not found']);
        $comment = trim($request->post('comment_rating'));
        $order->rating = 1;
        $order->comment_rating = $comment;
        $order->save();
        return $this->renderJson(true, []);
    }

    public function actionDislike($id)
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        if (!$order) return $this->renderJson(false, [], ['error' => 'Order not found']);
        $comment = trim($request->post('comment_rating'));
        if ($comment) {
            $order->rating = -1;
            $order->comment_rating = $comment;
            $order->save();
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], ['error' => 'For improving our service, please leave your message. Thank you.']);
        }
    }

    public function actionCancel($key)
    {
        $order = Order::findOne(['auth_key' => $key]);
        if (!$order) throw new NotFoundHttpException('Order is invalid');
        // Save cancel request
        $request = Yii::$app->request;
        $order->on(Order::EVENT_AFTER_UPDATE, function ($event) {
            $o = $event->sender;
            $o->log(sprintf("Sent cancel request"));
            // Send notification to saler
            $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
            $o->pushNotification(OrderNotification::NOTIFY_SALER_CANCEL_ORDER, $salerTeamIds);

            // Send notification to orderteam
            $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
            $o->pushNotification(OrderNotification::NOTIFY_ORDERTEAM_CANCEL_ORDER, $orderTeamIds);

            // Send notification to supplier
            $supplier = $o->workingSupplier;
            if ($supplier) {
                $o->pushNotification(OrderNotification::NOTIFY_SUPPLIER_CANCEL_ORDER, $supplier->supplier_id);
            }
        });
        $order->setScenario(Order::SCENARIO_CANCELORDER);
        $order->request_cancel = 1;
        $order->request_cancel_time = date('Y-m-d H:i:s');
        $order->request_cancel_description = $request->post('content');
        if ($order->save()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $order->getErrorSummary(true));
        }
    }

    public function actionSendComplain($id)
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        if (!$order) {
            return $this->asJson(['status' => false, 'errors' => 'Order is not found.']);
        }
        $content = $request->post('content');
        if (!$content) {
            return $this->asJson(['status' => false, 'errors' => 'Content is required.']);
        }
        $order->complain($content);
        return $this->asJson(['status' => true]);
    }

    public function actionTransaction()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['user_menu_active'] = 'user.transaction';
    	$request = Yii::$app->request;
        $today = date('Y-m-d');
        $firstOfMonth = date('Y-m-01');
        $filter = [
            'user_id' => Yii::$app->user->id,
            'start_date' => $request->get('start_date', $firstOfMonth),
            'end_date' => $request->get('end_date', $today),
            'status' => $request->get('status'),
        ];
        $form = new FetchHistoryTransactionForm($filter);

        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('transaction', [
            'models' => $models,
            'pages' => $pages,
            'filterForm' => $form,
        ]);
    }

    public function actionWallet()
    {
        $this->view->params['body_class'] = 'global-bg';
        $this->view->params['user_menu_active'] = 'user.wallet';
        $user = Yii::$app->user->getIdentity();
    	$request = Yii::$app->request;
        $today = date('Y-m-d');
        $firstOfMonth = date('Y-m-01');
        $promotion = $request->get('pro');
        $filter = [
            'user_id' => Yii::$app->user->id,
            'start_date' => $request->get('start_date', $firstOfMonth),
            'end_date' => $request->get('end_date', $today),
            'type' => $request->get('type'),
            'status' => $request->get('status'),
        ];
        $form = new FetchHistoryWalletForm($filter);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('wallet', [
            'models' => $models,
            'pages' => $pages,
            'filterForm' => $form,
            'coin' => $user->getWalletAmount(),
            'promotion' => $promotion
        ]);
    }

    public function actionEvidence($id)
    {
    	$request = Yii::$app->request;
        if ($request->isPost) {
            $transaction = PaymentTransaction::findOne($id);
            $files = Yii::$app->file->upload('evidence', "evidence/$id", true);
            $inputFile = reset($files);
            $transaction->evidence = $inputFile;
            $transaction->save();
            return $this->redirect($request->getReferrer());
        }
    }

    public function actionRemoveEvidence($id)
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $transaction = PaymentTransaction::findOne($id);
            $transaction->evidence = '';
            $transaction->save();
            return $this->redirect($request->getReferrer());
        }
    }

    public function actionOrderEvidence($id)
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $order = Order::findOne($id);
            $files = Yii::$app->file->upload('evidence', "order-evidence/$id", true);
            $inputFile = reset($files);
            $order->evidence = $inputFile;
            $order->save();
            return $this->redirect($request->getReferrer());
        }
    }

    public function actionRemoveOrderEvidence($id)
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $order = Order::findOne($id);
            $order->evidence = '';
            $order->save();
            return $this->redirect($request->getReferrer());
        }
    }
}