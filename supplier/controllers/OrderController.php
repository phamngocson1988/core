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
use supplier\forms\FetchOrderForm;
use supplier\forms\FetchOrderForm1;
use yii\data\Pagination;
use yii\helpers\Url;
use supplier\behaviors\OrderLogBehavior;
use supplier\behaviors\OrderMailBehavior;
use supplier\behaviors\OrderSupplierBehavior;
use supplier\forms\TakeOrderSupplierForm;
use supplier\forms\RejectOrderSupplierForm;
use supplier\forms\UpdateOrderToProcessingForm;
use supplier\forms\AddOrderQuantityForm;
use supplier\forms\UpdateOrderToCompletedForm;
use supplier\forms\UpdateOrderToPartialForm;
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
            'q' => $request->get('q'),
            'customer_phone' => $request->get('customer_phone'),
            'customer_id' => $request->get('customer_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => $request->get('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED,
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

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
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
            'request_start_date' => $request->get('request_start_date'),
            'request_end_date' => $request->get('request_end_date'),
            'status' => $request->get('status', [
                OrderSupplier::STATUS_APPROVE,
            ]),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new FetchOrderForm1($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('pending', [
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
            'request_start_date' => $request->get('request_start_date'),
            'request_end_date' => $request->get('request_end_date'),
            'status' => $request->get('status', [
                OrderSupplier::STATUS_PROCESSING,
            ]),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new FetchOrderForm1($data);
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

    public function actionCompleted()
    {
        $this->view->params['main_menu_active'] = 'order.completed';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'request_start_date' => $request->get('request_start_date'),
            'request_end_date' => $request->get('request_end_date'),
            'status' => $request->get('status', [
                OrderSupplier::STATUS_COMPLETED,
            ]),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new FetchOrderForm1($data);
        $command = $form->getCommand();
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

    public function actionPartial()
    {
        $this->view->params['main_menu_active'] = 'order.partial';
        $request = Yii::$app->request;
        $data = [
            'order_id' => $request->get('order_id'),
            'game_id' => $request->get('game_id'),
            'request_start_date' => $request->get('request_start_date'),
            'request_end_date' => $request->get('request_end_date'),
            'status' => $request->get('status', [
                OrderSupplier::STATUS_PARTIAL,
            ]),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new FetchOrderForm1($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('partial', [
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
            'q' => $request->get('q'),
            'customer_phone' => $request->get('customer_phone'),
            'customer_id' => $request->get('customer_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => $request->get('status', [
                Order::STATUS_CONFIRMED,
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
            'request_start_date' => $request->get('request_start_date'),
            'request_end_date' => $request->get('request_end_date'),
            'status' => $request->get('status', [
                OrderSupplier::STATUS_REQUEST,
            ]),
            'supplier_id' => Yii::$app->user->id,
        ];
        $form = new FetchOrderForm1($data);
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
        if ($form->validate()) {
            return $this->asJson(['status' => $form->reject()]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'errors' => $error]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $orderSupplier = OrderSupplier::findOne($id);
        if (!$orderSupplier || $orderSupplier->supplier_id != Yii::$app->user->id) throw new NotFoundHttpException('Not found');

        $order = $orderSupplier->order;
        if (!$order) throw new NotFoundHttpException('Not found');

        return $this->render('edit', [
            'order' => $order,
            'model' => $orderSupplier
        ]);
    }

    public function actionView($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        if (!$order) throw new NotFoundHttpException('Not found');
        $supplier = $order->supplier;
        if (!$supplier || $supplier->supplier_id != Yii::$app->user->id) throw new NotFoundHttpException('Not found');
        return $this->render('view', [
            'order' => $order,
        ]);
    }

    public function actionMoveToProcessing($id)
    {
        $form = new UpdateOrderToProcessingForm([
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
        $form = new UpdateOrderToCompletedForm([
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
            Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
            $order->attachBehavior('mail', OrderMailBehavior::className());
            $order->complain($content);
            $order->send(
                'admin_complain_order', 
                sprintf("[KingGems] - Your have a notification for order #%s", $order->id), [
                    'content' => $content, 
                    'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true)
            ]);
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
