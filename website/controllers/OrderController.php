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

    public function actionCancel($id)
    {
        // $order = Order::findOne(['auth_key' => $key]);
        // if (!$order) throw new NotFoundHttpException('Order is invalid');
        // // Save cancel request
        // $request = Yii::$app->request;
        // $order->on(Order::EVENT_AFTER_UPDATE, function ($event) {
        //     $o = $event->sender;
        //     $o->log(sprintf("Sent cancel request"));
        //     // Send notification to saler
        //     $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
        //     $o->pushNotification(OrderNotification::NOTIFY_SALER_CANCEL_ORDER, $salerTeamIds);

        //     // Send notification to orderteam
        //     $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
        //     $o->pushNotification(OrderNotification::NOTIFY_ORDERTEAM_CANCEL_ORDER, $orderTeamIds);

        //     // Send notification to supplier
        //     $supplier = $o->workingSupplier;
        //     if ($supplier) {
        //         $o->pushNotification(OrderNotification::NOTIFY_SUPPLIER_CANCEL_ORDER, $supplier->supplier_id);
        //     }
        // });
        // $order->setScenario(Order::SCENARIO_CANCELORDER);
        // $order->request_cancel = 1;
        // $order->request_cancel_time = date('Y-m-d H:i:s');
        // $order->request_cancel_description = $request->post('content');
        // if ($order->save()) {
        //     return $this->renderJson(true, []);
        // } else {
        //     return $this->renderJson(false, [], $order->getErrorSummary(true));
        // }
    }

    public function actionView() 
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $order = Order::findOne($id);
        if (!$order) {
            return $this->asJson(['status' => false, 'errors' => 'Order is not found']);
        }
        if ($order->customer_id != Yii::$app->user->id) {
            return $this->asJson(['status' => false, 'errors' => 'Order is not found']);
        }
        $paygate = Paygate::find()->where(['identifier' => $order->payment_method])->one();
        $model = new \website\forms\UpdateOrderForm(['id' => $id]);
        $model->loadData();
        return $this->asJson(['status' => true, 'data' => $this->renderPartial('view', [
            'payment' => $payment,
            'paygate' => $paygate,
            'model' => $model,
        ])]);
    }
}