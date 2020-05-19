<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;

// models
use website\models\Order;
// forms
use website\forms\FetchOrderForm;

class OrderController extends Controller
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
    	$userId = Yii::$app->user->id;
    	$request = Yii::$app->request;
    	$verifyingForm = new FetchOrderForm([
    		'customer_id' => $userId,
    		'status' => Order::STATUS_VERIFYING
    	]);
    	$verifyingOrders = $verifyingForm->getCommand()->all();

    	$form = new FetchOrderForm([
    		'customer_id' => $userId,
    		'status' => $request->get('status')
    	]);
    	if (!$form->status) {
    		$form->status = $form->fetchStatusList();
    	}
    	$command = $form->getCommand();
    	$pages = new Pagination(['totalCount' => $command->count()]);
        $orders = $command->offset($pages->offset)->limit($pages->limit)->all();
    	return $this->render('index', [
    		'verifyingOrders' => $verifyingOrders,
    		'orders' => $orders,
    		'search' => $form,
    		'pages' => $pages
    	]);
    }

    public function actionDetail($id)
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        return $this->renderPartial('detail', ['order' => $order]);
    }
}