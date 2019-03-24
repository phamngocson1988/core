<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\forms\EditUserForm;
use frontend\forms\ChangePasswordForm;
use frontend\forms\FetchHistoryOrderForm;
use frontend\forms\FetchHistoryTransactionForm;
use frontend\forms\FetchHistoryWalletForm;
use common\models\Order;
use common\models\UserWallet;
use common\models\Transaction;

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
        $order = Order::find(['customer_id' => $userId])->orderBy(['id' => SORT_DESC])->one();
        $wallet = UserWallet::find(['user_id' => $userId])->orderBy(['id' => SORT_DESC])->one();
        $transaction = Transaction::find(['user_id' => $userId])->orderBy(['id' => SORT_DESC])->one();
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
    	$model = ChangePasswordForm::findOne(Yii::$app->user->id);
    	if ($request->isPost) {
    		if ($model->load($request->post()) && $model->validate() && $model->change()) {
                Yii::$app->session->setFlash('success', 'You have updated successfully.');
                $this->redirect(['user/index']);
    		} else {
                Yii::$app->session->setFlash('error', 'There are something wrong!');
    		}
    		unset($_POST);
    	}
    	return $this->render('password', ['model' => $model]);
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
        
        return $this->render('detail', ['model' => $order]);
        // print_r($order);
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