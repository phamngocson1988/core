<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use common\components\Controller;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\forms\EditUserForm;
use frontend\forms\ChangePasswordForm;
use frontend\forms\FetchHistoryOrderForm;
use frontend\forms\FetchHistoryTransactionForm;
use frontend\forms\FetchHistoryWalletForm;
use frontend\forms\CompleteOrderForm;
use common\models\Order;
use common\models\UserWallet;
use common\models\Transaction;
use common\models\OrderComplains;

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
                'only' => ['index', 'profile', 'password'],
                'rules' => [
                    [
                        'actions' => ['index', 'profile', 'password', 'orders', 'order-detail'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $user = Yii::$app->user->getIdentity();
        $order = Order::find()->where(['customer_id' => $userId])->orderBy(['id' => SORT_DESC])->one();
        $wallet = UserWallet::find()->where(['user_id' => $userId])->orderBy(['id' => SORT_DESC])->one();
        $transaction = Transaction::find()->where(['user_id' => $userId])->orderBy(['id' => SORT_DESC])->one();
    	return $this->render('index', [
            'order' => $order,
            'wallet' => $wallet,
            'coin' => $user->getWalletAmount(),
            'transaction' => $transaction
        ]);
    }

    public function actionProfile()
    {
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
        $request = Yii::$app->request;
        $post = $request->post();
        $model = new ChangePasswordForm();
        
        if ($model->load($post) && $model->change()) {
            $model = new ChangePasswordForm();
            return $this->redirect(['user/index']);
        }

        return $this->render('password', [
            'model' => $model,
            'user' => Yii::$app->user->getIdentity(),
        ]);
    }

    public function actionOrders()
    {
        $request = Yii::$app->request;
        $today = date('Y-m-d');
        $firstOfMonth = date('Y-m-01');
        $filter = [
            'user_id' => Yii::$app->user->id,
            'start_date' => $request->get('start_date', $firstOfMonth),
            'end_date' => $request->get('end_date', $today),
            'game_id' => $request->get('game_id')
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

    public function actionDetail($key)
    {
        $order = Order::findOne(['auth_key' => $key]);
        if (!$order) throw new NotFoundHttpException("The order not found", 1);
        $complainModel = new OrderComplains();
        return $this->render('detail', [
            'model' => $order,
            'complainModel' => $complainModel
        ]);
    }

    public function actionConfirm($key)
    {
        $model = new CompleteOrderForm(['auth_key' => $key]);
        if ($model->save()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionSendComplain()
    {
        $request = Yii::$app->request;
        $model = new OrderComplains();
        $model->order_id = $request->post('order_id');
        $model->content = $request->post('content');
        $model->created_by = Yii::$app->user->id;
        if ($model->save()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionTransaction()
    {
    	$request = Yii::$app->request;
        $today = date('Y-m-d');
        $firstOfMonth = date('Y-m-01');
        $filter = [
            'user_id' => Yii::$app->user->id,
            'start_date' => $request->get('start_date', $firstOfMonth),
            'end_date' => $request->get('end_date', $today),
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
        $user = Yii::$app->user->getIdentity();
    	$request = Yii::$app->request;
        $today = date('Y-m-d');
        $firstOfMonth = date('Y-m-01');
        $filter = [
            'user_id' => Yii::$app->user->id,
            'start_date' => $request->get('start_date', $firstOfMonth),
            'end_date' => $request->get('end_date', $today),
            'type' => $request->get('type')
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
        ]);
    }
}