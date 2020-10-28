<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use supplier\models\Order;
use supplier\models\OrderFile;
use supplier\models\OrderComplainTemplate;
use supplier\models\OrderSupplier;
use supplier\models\Supplier;
use supplier\models\OrderComplains;
use supplier\forms\FetchOrderForm;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use supplier\behaviors\OrderLogBehavior;
use supplier\behaviors\OrderMailBehavior;
use supplier\behaviors\OrderSupplierBehavior;
use supplier\forms\TakeOrderSupplierForm;
use supplier\forms\RejectOrderSupplierForm;
use supplier\forms\UpdateOrderToProcessingForm;
use supplier\forms\AddOrderQuantityForm;
use supplier\forms\UpdateOrderToCompletedForm;
use supplier\forms\UpdateOrderToPartialForm;

// Notification
use supplier\components\notifications\OrderNotification;

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
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'request_start_date' => $request->get('request_start_date'),
            'request_end_date' => $request->get('request_end_date'),
            'status' => $request->get('status', [
                OrderSupplier::STATUS_APPROVE,
                OrderSupplier::STATUS_PROCESSING,
                OrderSupplier::STATUS_COMPLETED,
            ]),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new FetchOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionPending1()
    {
        $this->view->params['main_menu_active'] = 'order.pending';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'request_start_date' => $request->get('request_start_date'),
            'request_end_date' => $request->get('request_end_date'),
            'status' => $request->get('status'),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new \supplier\forms\FetchPendingOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();
        $complains = [];
        foreach ($models as $model) {
            if ($model->order->state) {
                $complains[] = OrderComplains::find()->select(["order_id", "MAX(created_at) as created_at"])
                ->where(["order_id" => $model->order_id])->asArray()->one();
            }
        }
        $complains = $complains ? ArrayHelper::map($complains, 'order_id', 'created_at') : $complains;
        return $this->render('pending', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'complains' => $complains,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionPending()
    {
        $this->view->params['main_menu_active'] = 'order.pending';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => $request->get('status'),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new \supplier\forms\FetchPendingShopForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();

        $firstComplains = $lastComplains = [];
        foreach ($models as $model) {
            $firstComplains[$model->order_id] = OrderComplains::find()
                ->where([
                    "order_id" => $model->order_id,
                    "object_name" => [OrderComplains::OBJECT_NAME_ADMIN, OrderComplains::OBJECT_NAME_SUPPLIER]
                ])
                // ->andWhere(['BETWEEN', 'created_at', $model->created_at, $model->updated_at])
                ->min('created_at');
            $lastComplains[$model->order_id] = OrderComplains::find()
                ->where([
                    "order_id" => $model->order_id,
                    "object_name" => OrderComplains::OBJECT_NAME_CUSTOMER
                ])
                // ->andWhere(['BETWEEN', 'created_at', $model->created_at, $model->updated_at])
                ->max('created_at');
        }

        return $this->render('pending', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'firstComplains' => $firstComplains,
            'lastComplains' => $lastComplains,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionProcessing1()
    {
        $this->view->params['main_menu_active'] = 'order.processing';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'request_start_date' => $request->get('request_start_date'),
            'request_end_date' => $request->get('request_end_date'),
            'status' => $request->get('status', [
                OrderSupplier::STATUS_PROCESSING,
            ]),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new FetchOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('processing', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionProcessing()
    {
        $this->view->params['main_menu_active'] = 'order.processing';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new \supplier\forms\FetchProcessingShopForm($data);
        $command = clone $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('processing', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCompleted()
    {
        $this->view->params['main_menu_active'] = 'order.completed';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new \supplier\forms\FetchCompletedShopForm($data);
        $command = clone $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('completed', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionConfirmed1()
    {
        $this->view->params['main_menu_active'] = 'order.confirmed';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'request_start_date' => $request->get('request_start_date'),
            'request_end_date' => $request->get('request_end_date'),
            'status' => $request->get('status', [
                OrderSupplier::STATUS_CONFIRMED,
            ]),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new FetchOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('confirmed', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionConfirmed()
    {
        $this->view->params['main_menu_active'] = 'order.confirmed';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new \supplier\forms\FetchConfirmedShopForm($data);
        $command = clone $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('confirmed', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCancellingOrder()
    {
        $this->view->params['main_menu_active'] = 'order.cancelling';
        $request = Yii::$app->request;
        $data = [
            'q' => $request->get('q'),
            'customer_phone' => $request->get('customer_phone'),
            'customer_id' => $request->get('customer_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => [Order::STATUS_PENDING, Order::STATUS_PROCESSING],
            'supplier_id' => Yii::$app->user->id,
            'supplier_status' => OrderSupplier::STATUS_APPROVE,
            'request_cancel' => 1
        ];
        $form = new FetchOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();                    
                            

        return $this->render('cancelling', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCancelledOrder()
    {
        $this->view->params['main_menu_active'] = 'order.cancelled';
        $request = Yii::$app->request;
        $data = [
            'q' => $request->get('q'),
            'customer_phone' => $request->get('customer_phone'),
            'customer_id' => $request->get('customer_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => $request->get('status', [
                Order::STATUS_CANCELLED,
            ]),
            'supplier_id' => Yii::$app->user->id,
            'supplier_status' => OrderSupplier::STATUS_APPROVE,
        ];
        $form = new FetchOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();                    

        return $this->render('cancelled', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionWaiting()
    {
        $this->view->params['main_menu_active'] = 'order.waiting';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new \supplier\forms\FetchWaitingShopForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('waiting', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCountWaiting()
    {
        $waitingForm = new \supplier\forms\FetchWaitingShopForm(['supplier_id' => Yii::$app->user->id]);
        $newWaitingOrderTotal = $waitingForm->count();
        return $this->asJson(['status' => true, 'count' => $newWaitingOrderTotal]);
    }

    public function actionAccept($id)
    {
        $form = new TakeOrderSupplierForm([
            'id' => $id,
            'supplier_id' => Yii::$app->user->id

        ]);
        if ($form->validate()) {
            return $this->asJson(['status' => $form->approve()]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'errors' => $error]);
    }

    public function actionReject($id)
    {
        $form = new RejectOrderSupplierForm([
            'id' => $id,
            'supplier_id' => Yii::$app->user->id
        ]);
        $pendingUrl = Url::to(['order/pending'], true);
        if ($form->validate()) {
            return $this->asJson(['status' => $form->reject(), 'pendingUrl' => $pendingUrl]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'errors' => $error, 'pendingUrl' => $pendingUrl]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $orderSupplier = OrderSupplier::findOne($id);
        if (!$orderSupplier || $orderSupplier->supplier_id != Yii::$app->user->id) throw new NotFoundHttpException('Not found');

        $order = $orderSupplier->order;
        if (!$order) throw new NotFoundHttpException('Not found');


        $countComplain = OrderComplains::find()
        ->where(['order_id' => $orderSupplier->order_id])
        ->andWhere(['created_by' => $orderSupplier->supplier_id])
        ->count();

        return $this->render('edit', [
            'order' => $order,
            'model' => $orderSupplier,
            'countComplain' => $countComplain
        ]);
    }

    public function actionView($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $orderSupplier = OrderSupplier::findOne($id);
        if (!$orderSupplier || $orderSupplier->supplier_id != Yii::$app->user->id) throw new NotFoundHttpException('Not found');

        $order = $orderSupplier->order;
        if (!$order) throw new NotFoundHttpException('Not found');


        $countComplain = OrderComplains::find()
        ->where(['order_id' => $orderSupplier->order_id])
        ->andWhere(['created_by' => $orderSupplier->supplier_id])
        ->count();

        return $this->render('view', [
            'order' => $order,
            'model' => $orderSupplier,
            'countComplain' => $countComplain
        ]);
    }

    public function actionMoveToProcessing($id) 
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $form = new UpdateOrderToProcessingForm([
                'id' => $id,
                'supplier_id' => Yii::$app->user->id
            ]);
            if ($form->move()) {
                return $this->asJson(['status' => true, 'editUrl' => Url::to(['order/edit', 'id' => $id])]);
            } else {
                $errors = $form->getErrorSummary(false);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'errors' => $error]);
            }
        } else {
            return $this->renderPartial('_move_processing_modal', [
                'id' => $id,
            ]);    
        }
    }

    public function actionMoveToPartial($id)
    {
        $form = new UpdateOrderToPartialForm([
            'id' => $id,
            'supplier_id' => Yii::$app->user->id

        ]);
        if ($form->move()) {
            return $this->asJson(['status' => true]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'errors' => $error]);
    }

    public function actionMoveToCompleted($id)
    {
        $request = Yii::$app->request;
        $form = new UpdateOrderToCompletedForm([
            'id' => $id,
            'supplier_id' => Yii::$app->user->id,
            'doing' => $request->post('doing', 0)
        ]);
        if ($form->move()) {
            return $this->asJson(['status' => true]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'errors' => $error]);
    }

    public function actionAddQuantity($id)
    {
        $request = Yii::$app->request;
        $form = new AddOrderQuantityForm([
            'id' => $id,
            'supplier_id' => Yii::$app->user->id,
            'doing' => $request->post('doing', 0)
        ]);
        if ($form->add()) {
            return $this->asJson(['status' => true, 'data' => ['total' => $form->getFinalDoing()]]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'errors' => $error]);
    }

    public function actionUpdatePercent($id)
    {
        $request = Yii::$app->request;
        $orderSupplier = OrderSupplier::findOne($id);
        $orderSupplier->percent = $request->get('percent');
        return $this->asJson(['status' => $orderSupplier->save()]);
    }

    public function actionAddEvidenceImage($id)
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        if (!$order) return $this->renderJson(false, [], 'Error');
        $type = $request->get('type', 'before');
        $model = new OrderFile();
        $model->setScenario(OrderFile::SCENARIO_CREATE);
        $model->order_id = $id;
        $model->file_type = ($type == 'after') ? (OrderFile::TYPE_EVIDENCE_AFTER) : (OrderFile::TYPE_EVIDENCE_BEFORE);
        if ($model->load($request->post()) && $model->save()) {
            return $this->renderJson(true, ['html' => $this->renderPartial('_evidence', ['images' => $order->getEvidencesByType($model->file_type), 'can_edit' => Yii::$app->user->can('edit_order', ['order' => $order])])]);
        } else {
            return $this->renderJson(false);
        } 
    }

    public function actionRemoveEvidenceImage($id)
    {
        $request = Yii::$app->request;
        $model = OrderFile::findOne($id);
        return $this->renderJson($model->delete());
    }

    public function actionComplain($id)
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        $content = $request->post('content');
        if (trim($content)) {
            $order->complain($content);
            $order->pushNotification(OrderNotification::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE, $order->customer_id, ['message' => $content]);
            return $this->renderJson(true);
        }
        return $this->renderJson(false, null, ['error' => 'Nội dung bị rỗng']);
    }

    public function actionTemplate($id) 
    {
        $templateList = OrderComplainTemplate::find()->all();
        return $this->renderPartial('_template', [
            'template_list' => $templateList,
            'id' => $id
        ]);
    }
}
