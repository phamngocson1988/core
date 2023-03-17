<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

// models
use website\models\Order;
use website\models\OrderFile;
use website\models\Paygate;
// forms
use website\forms\FetchOrderForm;
use common\models\PaymentCommitmentOrder;


class OrderController extends Controller
{
	public function behaviors()
    {
        return [
            'blockip' => [
                'class' => \website\components\filters\BlockIpAccessControl::className(),
            ],
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

    public function actionBulk()
    {
    	$userId = Yii::$app->user->id;
    	$request = Yii::$app->request;
    	$models = PaymentCommitmentOrder::find()->where([
            'user_id' => Yii::$app->user->id,
            'status' => PaymentCommitmentOrder::STATUS_PENDING,
            'parent' => 0,
        ])->all();
        $bulks = ArrayHelper::getColumn($models, 'object_key');
        $orders = Order::find()->where(['bulk' => $bulks])
            ->indexBy('id')
            ->all();
        $orders = ArrayHelper::toArray($orders, [
            'website\models\Order' => [
                'id', 'customer_name', 'game_title', 'quantity', 'total_unit', 'bulk', 'payment_id',
                'payment_data',
                'payment_data_content' => function ($order) {
                    return strlen($order->getPaymentData());
                },
            ],
        ]);
        
        $mappingBulks = ArrayHelper::index($orders, null, 'bulk');
        $mappingOrders = [];
        foreach ($models as $model) {
            $childrenOrders = ArrayHelper::getValue($mappingBulks, $model->bulk, []);
            $orderDetail['game_title'] = count($childrenOrders) ? $childrenOrders[0]['game_title'] : '';
            $orderDetail['quantity'] = count($childrenOrders) ? array_sum(array_column($childrenOrders, 'quantity')) : 0;
            $orderDetail['total_unit'] = count($childrenOrders) ? array_sum(array_column($childrenOrders, 'total_unit')) : 0;
            $orderDetail['payment_data'] = count($childrenOrders) ? $childrenOrders[0]['payment_data'] : '';
            $orderDetail['payment_data_content'] = count($childrenOrders) ? $childrenOrders[0]['payment_data_content'] : '';
            $orderDetail['total_amount'] = $model->total_amount;
            $orderDetail['currency'] = $model->currency;
            $orderDetail['payment_method'] = $model->payment_method;
            $orderDetail['payment_type'] = $model->payment_type;
            $mappingOrders[$model->id] = $orderDetail;
            
        }
    	return $this->render('bulk', [
    		'models' => $models,
            'mappingOrders' => $mappingOrders
    	]);
    }

    public function actionDetail($id)
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        if ($order->customer_id != Yii::$app->user->id) {
            return '<div class="modal-header d-block"><h2 class="modal-title text-center w-100 text-red text-uppercase">Not found</h2></div>';
        }
        return $this->renderPartial('detail', ['order' => $order]);
    }

    public function actionFiles($id)
    {
        $order = Order::findOne($id);
        $files = $order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_BEFORE);
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
            return $this->asJson(['status' => false, 'errors' => $model->getFirstErrorMessage()]);
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
        if (!$order->isVerifyingOrder()) {
            return $this->asJson(['status' => false, 'errors' => 'Order is not found']);
        }
        $model = new \website\forms\UpdateOrderForm(['id' => $id]);
        $model->loadData();
        return $this->renderPartial('view', [
            'order' => $order,
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
            if ($model->update()) {
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

        $files = Yii::$app->file->upload('file_message', "order_message/$id", true);
        $content = $request->post('content');
        $type = 'text';
        if ($files) {
            $inputFile = reset($files);
            $content = $inputFile;
            $type = 'image';
        }
        if (!$content) {
            return $this->asJson(['status' => false, 'errors' => 'Content is required.']);
        }
        $order->complain($content, $type);
        $supplier = $order->workingSupplier;
        if ($supplier) {
            $order->pushNotification(\website\components\notifications\OrderNotification::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE, $supplier->supplier_id);
        }


        
        return $this->asJson(['status' => true]);
    }

    public function actionCheckNewMessage($ids) 
    {
        $request = Yii::$app->request;
        $ids = $request->get('ids');
        $ids = trim($ids);
        if (!$ids) {
            return $this->asJson(['status' => false]);
        }
        $ids = explode(",", $ids);
        $orders = Order::findAll($ids);
        $mapping = [];
        foreach ($orders as $order) {
            $mapping[$order->id] = $order->hasNewMessage();
        }
        return $this->asJson(['status' => true, 'mapping' => $mapping]);
    }
}