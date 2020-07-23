<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;

// models
use website\models\Order;
use website\models\OrderFile;
use website\models\Paygate;
// forms
use website\forms\FetchOrderForm;
use website\components\payment\PaymentGatewayFactory;


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
            'status' => $request->get('status'),
            'start_date' => $request->get('start_date'),
    		'end_date' => $request->get('end_date'),
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

    public function actionFiles($id)
    {
        $order = Order::findOne($id);
        $files = $order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_AFTER);
        return $this->renderPartial('files', ['files' => $files, 'order' => $order]);
    }

    public function actionSurvey($id)
    {
        $request = Yii::$app->request;
        $model = new \website\forms\SurveyOrderForm([
            'id' => $id,
            'rating' => $request->get('rating', 1),
        ]);
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate() && $model->survey()) {
                return $this->asJson(['status' => true, 'rating' => $model->rating]);
            } else {
                return $this->asJson(['status' => false, 'errors' => $model->getErrorSummary(true)]);
            }
        }
        return $this->renderPartial('survey', ['model' => $model]);
    }

    public function actionCancel($id)
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \website\forms\CancelOrderForm(['id' => $id]);
        if ($model->validate() && $model->cancel()) {
            return $this->asJson(['status' => true]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $order->getErrorSummary(true)]);
        }
    }

    public function actionView($id) 
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        if (!$order) {
            return $this->asJson(['status' => false, 'errors' => 'Order is not found']);
        }
        if ($order->customer_id != Yii::$app->user->id) {
            return $this->asJson(['status' => false, 'errors' => 'Order is not found']);
        }
        $paygate = PaymentGatewayFactory::getClient($order->payment_method);
        $model = new \website\forms\UpdateOrderForm(['id' => $id]);
        $model->loadData();
        return $this->renderPartial('view', [
            'order' => $order,
            'paygate' => $paygate,
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = new \website\forms\UpdateOrderForm(['id' => $id]);
        if ($model->load($request->post())) {
            $files = Yii::$app->file->upload('evidence', "evidence/$id", true);
            if ($files) {
                $inputFile = reset($files);
                $model->evidence = $inputFile;
            }
            if ($model->validate() && $model->update()) {
                return $this->asJson(['status' => true, 'message' => sprintf("You have updated transaction #%s successfully.", $id)]);
            } else {
                $errors = $model->getErrorSummary(true);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'errors' => $error]);
            }
        }
    }

    public function actionConfirm($id)
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \website\forms\ConfirmOrderForm(['id' => $id]);
        if ($model->validate() && $model->confirm()) {
            return $this->asJson(['status' => true]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $order->getErrorSummary(true)]);
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
        // $supplier = $order->workingSupplier;
        // if ($supplier) {
        //     $order->pushNotification(OrderNotification::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE, $supplier->supplier_id);
        // }
        return $this->asJson(['status' => true]);
    }
}