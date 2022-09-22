<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\forms\FetchOrderForm;
use backend\forms\FetchMyOrderForm;
use backend\forms\CreateOrderForm;
use backend\forms\EditOrderForm;
use backend\forms\EditOrderItemForm;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\User;
use backend\models\Game;
use backend\models\OrderFile;
use backend\forms\UpdateOrderStatusProcessing;
use common\models\OrderComplainTemplate;
use backend\forms\MyCustomerReportForm;
use backend\forms\CancelOrderForm;
use backend\forms\FetchOrderByFeedback;
use backend\models\SupplierGame;
use backend\models\Supplier;
use backend\forms\FetchSupplierForm;
use backend\forms\AssignOrderSupplierForm;
use backend\forms\RetakeOrderSupplierForm;
use common\components\helpers\FormatConverter;


use backend\events\OrderEventHandler;
use backend\models\OrderComplains;
use backend\forms\ConfirmOrderForm;
use backend\forms\AddOrderQuantityForm;
use backend\forms\UpdateOrderToCompletedForm;
use backend\forms\UpdateOrderToPartialForm;
use backend\forms\StopOrderForm;
use backend\forms\ApproveCancelOrder;

use backend\models\UserReseller;
use backend\models\GameMethod;
use backend\models\OrderSupplier;
use backend\models\Promotion;
use common\models\Country;

// Notification
use backend\components\notifications\OrderNotification;

// use backend\forms\UpdateOrderStatusPending;

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
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'game_id' => $request->get('game_id'),
            'payment_method' => $request->get('payment_method'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => $request->get('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_PARTIAL,
                Order::STATUS_COMPLETED,
                Order::STATUS_CONFIRMED,
            ]),
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

    public function actionVerifying()
    {
        $this->view->params['main_menu_active'] = 'order.verifying';
        $request = Yii::$app->request;
        $data = [
            'q' => $request->get('q'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'game_id' => $request->get('game_id'),
            'payment_method' => $request->get('payment_method'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => Order::STATUS_VERIFYING,
        ];
        $form = new FetchOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('verifying', [
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
            'id' => $request->get('id'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'payment_method' => $request->get('payment_method'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];
        $form = new \backend\forms\FetchPendingShopForm($data);
        $command = $form->getCommand();
        $command->with('game');
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

    public function actionPendingInformation()
    {
        $this->view->params['main_menu_active'] = 'order.pendinginformation';
        $request = Yii::$app->request;
        $data = [
            'id' => $request->get('id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'saler_id' => $request->get('saler_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'state' => $request->get('state')
        ];
        $form = new \backend\forms\FetchPendingInformationShopForm($data);
        $command = $form->getCommand();
        $command->with('game');
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();                    
        $lastComplains = [];
        foreach ($models as $model) {
            $complain = OrderComplains::find()
            ->select(["order_id", "created_at", "content", "content_type"])
            ->where(["order_id" => $model->id])
            ->orderBy(['id' => SORT_DESC])
            ->asArray()->one();
            if ($complain) {
                $lastComplains[$model->id] = $complain;
            }
        }

        $orderIds = ArrayHelper::getColumn($models, 'id');
        $firstComplains = OrderComplains::find()
        ->select(["order_id", "created_at"])
        ->where(["in", "order_id", $orderIds])
        ->groupBy("order_id")
        ->indexBy("order_id")
        ->asArray()->all();

        return $this->render('pending-information', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'lastComplains' => $lastComplains,
            'firstComplains' => $firstComplains,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionProcessing1()
    {
        $this->view->params['main_menu_active'] = 'order.processing';
        $request = Yii::$app->request;
        $data = [
            'q' => $request->get('q'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'payment_method' => $request->get('payment_method'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => Order::STATUS_PROCESSING,
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
            'id' => $request->get('id'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'payment_method' => $request->get('payment_method'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];
        $form = new \backend\forms\FetchProcessingShopForm($data);
        $command = $form->getCommand();
        $command->with('game');
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

    public function actionPartial()
    {
        $this->view->params['main_menu_active'] = 'order.partial';
        $request = Yii::$app->request;
        $data = [
            'id' => $request->get('id'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'payment_method' => $request->get('payment_method'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];
        $form = new \backend\forms\FetchPartialShopForm($data);
        $command = $form->getCommand();
        $command->with('game');
        $pages = new Pagination(['totalCount' => $command->count()]);
        $table = Order::tableName();
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(["$table.id" => SORT_DESC])
                            ->all();                 

        return $this->render('partial', [
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
            'id' => $request->get('id'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'payment_method' => $request->get('payment_method'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];
        $form = new \backend\forms\FetchCompletedShopForm($data);
        $command = clone $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();                    

        // supplier name
        $supplierIds = ArrayHelper::getColumn($models, 'supplier_id');
        $suppliers = User::find()->where(['id' => $supplierIds])->indexBy('id')->all();
        return $this->render('completed', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'suppliers' => $suppliers,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionConfirmed()
    {
        $this->view->params['main_menu_active'] = 'order.confirmed';
        $request = Yii::$app->request;
        $mode = $request->get('mode');
        $data = [
            'id' => $request->get('id'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'payment_method' => $request->get('payment_method'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];
        $form = new \backend\forms\FetchConfirmedShopForm($data);
        // if ($mode === 'export') {
        //     $fileName = date('YmdHis') . 'danh-don-hang-da-xac-nhan.xls';
        //     return $form->export($fileName);
        // }

        $table = Order::tableName();
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(["$table.id" => SORT_DESC])
                            ->all();       

        // supplier name
        $supplierIds = ArrayHelper::getColumn($models, 'supplier_id');
        $suppliers = User::find()->where(['id' => $supplierIds])->indexBy('id')->all();

        // Check admin/supplier message (required by Thinh (27/8) via chat)
        $orderIds = ArrayHelper::getColumn($models, 'id');
        $complains = OrderComplains::find()
        ->where(['in', 'order_id', $orderIds])             
        ->andWhere(['in', 'object_name', ['supplier', 'admin']])
        ->groupBy(['order_id'])
        ->select(['order_id'])
        ->all();
        $existStaffComplainIds = ArrayHelper::getColumn($complains, 'order_id');
        return $this->render('confirmed', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
            'suppliers' => $suppliers,
            'existStaffComplainIds' => $existStaffComplainIds
        ]);
    }

    public function actionCancellingOrder()
    {
        $this->view->params['main_menu_active'] = 'order.cancelling';
        $request = Yii::$app->request;
        $data = [
            'q' => $request->get('q'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'game_id' => $request->get('game_id'),
            'payment_method' => $request->get('payment_method'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => [Order::STATUS_VERIFYING, Order::STATUS_PENDING, Order::STATUS_PROCESSING],
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
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'payment_method' => $request->get('payment_method'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => Order::STATUS_CANCELLED,
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

    public function actionCancel()
    {
        $this->view->params['main_menu_active'] = 'order.cancel';
        $request = Yii::$app->request;
        $data = [
            'id' => $request->get('id'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'payment_method' => $request->get('payment_method'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];
        $form = new \backend\forms\FetchCancelShopForm($data);
        $mode = $request->get('mode');
        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'don-hang-co-cancel.xls';
            return $form->export($fileName);
        }
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['request_cancel' => SORT_DESC, 'created_at' => SORT_DESC])
                            ->all();                    
                            

        return $this->render('cancel', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionFeedbackOrder()
    {
        $this->view->params['main_menu_active'] = 'order.feedback';
        $request = Yii::$app->request;
        $mode = $request->get('mode');
        $form = new FetchOrderByFeedback([
            'rating' => $request->get('rating'),
            'created_at_start' => $request->get('created_at_start'),
            'created_at_end' => $request->get('created_at_end'),
        ]);
        $command = $form->getCommand();
        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'don-hang-co-feedback.xls';
            return $form->export($fileName);
        }
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();

        return $this->render('feedback-order', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
            'search' => $form
        ]);
    }

    public function actionEdit($id)
    {
        $order = Order::findOne($id);
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        $templateList = OrderComplainTemplate::find()->all();
        return $this->render('edit', [
            'order' => $order,
            'stopModel' => new StopOrderForm(),
            'template_list' => $templateList
        ]);
    }

    public function actionView($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        return $this->render('view', [
            'order' => $order,
        ]);
    }

    public function actionMoveToPending($id)
    {
        $model = Order::findOne($id);
        if (!$model) return $this->asJson(['status' => false, 'error' => 'Đơn hàng không tồn tại']);
        if (!$model->isVerifyingOrder()) return $this->asJson(['status' => false, 'error' => 'Không thể chuyển trạng thái']);
        $request = Yii::$app->request;
        $model->setScenario(Order::SCENARIO_GO_PENDING);
        $model->on(Order::EVENT_AFTER_UPDATE, function ($event) {
            $order = $event->sender;
            $order->log(sprintf("Moved to pending with payment_id: %s", $order->payment_id));
            $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
            $order->pushNotification(OrderNotification::NOTIFY_ORDERTEAM_NEW_PENDING, $orderTeamIds);
            $order->pushNotification(OrderNotification::NOTIFY_CUSTOMER_PENDING_ORDER, $order->customer_id);
        });
        if (!$model->auth_key) $model->generateAuthKey();
        $model->payment_type = 'offline';
        $model->status = Order::STATUS_PENDING;
        $model->pending_at = date('Y-m-d H:i:s');
        if ($model->load($request->post()) && $model->save()) {
            return $this->renderJson(true, ['next' => Url::to(['order/index'])]);
        } else {
            $errors = $model->getErrorSummary(false);
            $error = reset($errors);
            return $this->asJson(['status' => false, 'errors' => $error]);
        }
    }

    public function actionMoveToProcessing($id)
    {
        $model = Order::findOne($id);
        if (!$model) return $this->asJson(['status' => false, 'error' => 'Đơn hàng không tồn tại']);
        if (!$model->isPendingOrder()) return $this->asJson(['status' => false, 'error' => 'Không thể chuyển trạng thái']);
        $request = Yii::$app->request;
        $model->setScenario(Order::SCENARIO_GO_PROCESSING);
        $model->status = Order::STATUS_PROCESSING;
        $model->state = new \yii\db\Expression('NULL');;
        $model->process_start_time = date('Y-m-d H:i:s');
        $model->processing_at = date('Y-m-d H:i:s');
        $model->on(Order::EVENT_AFTER_UPDATE, function($event) {
            $order = $event->sender;
            Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
            $order->log("Moved to processing");
        });

        return $this->renderJson($model->save());
    }

    public function actionMoveToPartial($id)
    {
        $form = new UpdateOrderToPartialForm([
            'id' => $id,

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
        ]);
        if ($form->move()) {
            return $this->asJson(['status' => true]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'errors' => $error]);
    }

    public function actionMoveToConfirmed($id)
    {
        $model = new ConfirmOrderForm(['id' => $id]);
        if ($model->save()) {
            return $this->asJson(['status' => true]);
        } else {
            $errors = $model->getErrorSummary(false);
            $error = reset($errors);
            return $this->asJson(['status' => false, 'errors' => $error]);
        }
    }

    public function actionTaken($id)
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        if (!$order) {
            $message = "Order #id not found";
            Yii::$app->session->setFlash('error', $message);
            return $this->asJson(['status' => false, 'error' => $message]);
        }
        if ($order->isCompletedOrder() || $order->isConfirmedOrder()) {
            $message = "Order $id is finished";
            Yii::$app->session->setFlash('error', $message);
            return $this->asJson(['status' => false, 'error' => $message]);
        }
        $order->attachBehavior('assign', \backend\behaviors\OrderBehavior::className());
        if ($order->assignOrderTeam(Yii::$app->user->id)) {
            // Yii::$app->session->setFlash('success', "You have taken order #$id successfully.");
            return $this->asJson(['status' => true]);
        }
    }

    public function actionAssign($id)
    {
        $request = Yii::$app->request;
        $userId = $request->post('user_id');
        $order = Order::findOne($id);
        if (!$order) {
            $message = "Order #id not found";
            Yii::$app->session->setFlash('error', $message);
            return $this->asJson(['status' => false, 'error' => $message]);
        }
        if ($order->isCompletedOrder() || $order->isConfirmedOrder()) {
            $message = "Order $id is finished";
            Yii::$app->session->setFlash('error', $message);
            return $this->asJson(['status' => false, 'error' => $message]);
        }
        $order->attachBehavior('assign', \backend\behaviors\OrderBehavior::className());
        if ($order->assignOrderTeam($userId)) {
            // Yii::$app->session->setFlash('success', "You have assign order #$id successfully.");
            return $this->asJson(['status' => true]);
        }
    }

    public function actionAssignSaler($id)
    {
        $request = Yii::$app->request;
        $userId = $request->post('user_id');
        $order = Order::findOne($id);
        if (!$order) {
            $message = "Order #id not found";
            Yii::$app->session->setFlash('error', $message);
            return $this->asJson(['status' => false, 'error' => $message]);
        }
        $order->saler_id = $userId;
        if ($order->save()) {
            return $this->asJson(['status' => true]);
        }
    }

    public function actionComplain($id)
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        $content = $request->post('content');
        if (trim($content)) {
            $order->complain($content);
            if (Yii::$app->user->isRole('orderteam')) {
                $order->pushNotification(OrderNotification::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE, $order->customer_id, ['message' => $content]);
            }
            if (Yii::$app->user->isRole('saler') && $order->supplier) {
                $supplier = $order->workingSupplier;
                if ($supplier) {
                    $order->pushNotification(OrderNotification::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE, $supplier->supplier_id);
                }

            }
            return $this->renderJson(true);
        }
        return $this->renderJson(false, null, ['error' => 'Nội dung bị rỗng']);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = Order::findOne($id);
        $model->log("Delete order. Current Status is %s", $model->status);
        if ($model && $model->delete()) {
            Yii::$app->session->setFlash('success', "You have deleted order #$id successfully.");
            return $this->renderJson(true, ['url' => Url::to(['order/index'])]);
        } else {
            $message = ($model) ? reset($form->getErrorSummary(false)) : "Order #$id not found";
            Yii::$app->session->setFlash('error', $message);
            return $this->renderJson(false, [], $message);
        }
    }

    public function actionAddQuantity($id)
    {
        $request = Yii::$app->request;
        $form = new AddOrderQuantityForm([
            'id' => $id,
            'doing' => $request->post('doing_unit', 0)
        ]);
        if ($form->add()) {
            return $this->asJson(['status' => true, 'data' => ['total' => $form->getFinalDoing()]]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'errors' => $error]);
    }

    public function actionApprove($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $form = new \backend\forms\ApproveCancelOrder(['id' => $id]);
            $viewUrl = Url::to(['order/view', 'id' => $id]);
            if ($form->validate() && $form->approve()) {
                return $this->asJson(['status' => true, 'view_url' => $viewUrl]);
            } else {
                $errors = $form->getErrorSummary(false);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'errors' => $error]);
            }
        }
        return $this->asJson(['status' => false, 'errors' => 'Not found']);
    }

    public function actionDisapprove($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $form = new \backend\forms\DenyCancelOrder(['id' => $id]);
            $viewUrl = Url::to(['order/view', 'id' => $id]);
            if ($form->validate() && $form->deny()) {
                return $this->asJson(['status' => true, 'view_url' => $viewUrl]);
            } else {
                $errors = $form->getErrorSummary(false);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'errors' => $error]);
            }
        }
        return $this->asJson(['status' => false, 'errors' => 'Not found']);

        // $request = Yii::$app->request;
        //     $model = Order::findOne($id);
        //     $model->on(Order::EVENT_AFTER_UPDATE, function ($event) {
        //         // Save a complain
        //         $order = $event->sender;
        //         $complain = new OrderComplains();
        //         $complain->order_id = $order->id;
        //         $complain->content = sprintf("Your request is cancelled by admin");
        //         $complain->save();
        //     });
        //     $model->on(Order::EVENT_AFTER_UPDATE, function($event) {
        //         $order = $event->sender;
        //         $order->log(sprintf("Disapproved to be cancelled when status is %s", $order->status));
        //     });
        //     $model->request_cancel = 0;
        //     return $this->renderJson($model->save());
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

    public function actionStop()
    {
        $request = Yii::$app->request;
        $model = new StopOrderForm();
        if ($model->load($request->post()) && $model->stop()) {
            return $this->asJson(['status' => true]);
        } else {
            $errors = $model->getErrorSummary(false);
            $error = reset($errors);
            return $this->asJson(['status' => false, 'errors' => $error]);
        }
    }

    public function actionSendMailVerifyingOrder($id)
    {
        $order = Order::findOne($id);
        if ($order) {
            $order->send('admin_notify_order_failure', '[KINGGEMS]-FAILED TRANSACTION');
        }
        return $this->renderJson(true);
    }

    public function actionAssignSupplier($id) 
    {
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        $model = new AssignOrderSupplierForm();
        if ($request->isPost) {
            $model->load($request->post());
            $model->order_id = $id;
            $model->requester = Yii::$app->user->id;
            if ($model->assign()) {
                return $this->asJson(['status' => true]);
            }
            $errors = $model->getErrorSummary(true);
            $error = reset($errors);
            return $this->asJson(['status' => false, 'error' => $error]);
        }
        // Fetch all supplier register this game
        $supplierGames = SupplierGame::find()
        ->where([
            'game_id' => $order->game_id,
            'status' => SupplierGame::STATUS_ENABLED,
        ])
        ->select(['supplier_id', 'price'])
        ->orderBy(['price' => SORT_ASC])
        ->asArray()
        ->all();
        $supplierIds = ArrayHelper::getColumn($supplierGames, 'supplier_id');
        $suppliers = Supplier::find()
        ->where(['status' => Supplier::STATUS_ENABLED])
        ->andWhere(['in', 'user_id', $supplierIds])
        ->indexBy('user_id')
        ->with('user')
        ->all();

        // Mapping price
        $supplierPrice = ArrayHelper::map($supplierGames, 'supplier_id', 'price');
        
        // Find processing order
        $processingOrders = Order::find()
        ->where(['status' => Order::STATUS_PROCESSING])
        ->andWhere(['in', 'supplier_id', $supplierIds])
        ->groupBy('supplier_id')
        ->select(['supplier_id', 'COUNT(*) as count'])
        ->asArray()
        ->all();
        $processingOrder = ArrayHelper::map($processingOrders, 'supplier_id', 'count');

        // Find duration time
        $completedOrders = Order::find()
        ->where(['status' => Order::STATUS_COMPLETED])
        ->andWhere(['in', 'supplier_id', $supplierIds])
        ->groupBy('supplier_id')
        ->select(['supplier_id', 'AVG(process_duration_time) as count'])
        ->asArray()
        ->all();
        $completedOrder = ArrayHelper::map($completedOrders, 'supplier_id', 'count');


        $supplierList = [];
        // foreach ($suppliers as $supplier) {
        //     $supplierId = $supplier->user_id;
        //     $supplierName = $supplier->user->name;
        //     $price = ArrayHelper::getValue($supplierPrice, $supplierId, 0);
        //     $count = ArrayHelper::getValue($processingOrder, $supplierId, 0);
        //     $avgTime = ArrayHelper::getValue($completedOrder, $supplierId, 0);
        //     $avgTimeFormat = FormatConverter::countDuration((int)$avgTime);
        //     $supplierList[$supplierId] = sprintf("%s - Price %s - Completed %s - Average %s", $supplierName, number_format($price), $count, $avgTimeFormat);
        // }
        foreach ($supplierPrice as $supplierId => $price) {
            if (!isset($suppliers[$supplierId])) continue;
            $supplier = $suppliers[$supplierId];
            $supplierName = $supplier->user->name;
            $count = ArrayHelper::getValue($processingOrder, $supplierId, 0);
            $avgTime = ArrayHelper::getValue($completedOrder, $supplierId, 0);
            $avgTimeFormat = FormatConverter::countDuration((int)$avgTime);
            $supplierList[$supplierId] = sprintf("%s - Price %s - Completed %s - Average %s", $supplierName, number_format($price), $count, $avgTimeFormat);
        }

        return $this->renderPartial('assign-supplier', [
            'id' => $id,
            'suppliers' => $supplierList,
            'model' => $model,
            'order' => $order,
            'ref' => Url::to($request->getUrl(), true)
        ]);
    }

    public function actionRemoveSupplier($id)
    {
        $request = Yii::$app->request;
        $model = new RetakeOrderSupplierForm([
            'order_id' => $id,
            'requester' => Yii::$app->user->id
        ]);
        if ($model->validate() && $model->retake()) {
            return $this->asJson(['status' => true]);
        } else {
            $errors = $model->getErrorSummary(true);
            $error = reset($errors);
            return $this->asJson(['status' => false, 'errors' => $error]);
        }
    }

    public function actionReport()
    {
        $this->view->params['main_menu_active'] = 'order.report';
        $request = Yii::$app->request;
        $data = [
            'saler_id' => $request->get('saler_id'),
            'supplier_id' => $request->get('supplier_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => $request->get('status'),
            'date_time_type' => $request->get('date_time_type'),
        ];
        $mode = $request->get('mode');
        $form = new \backend\forms\ReportShopForm($data);
        $fileName = date('YmdHis') . 'thong-ke-don-hang.xls';
        $role = '';
        $user = Yii::$app->user;
        if ($user->can('admin')) $role = 'admin';
        elseif ($user->can('admin')) $role = 'admin';
        elseif ($user->can('accounting')) $role = 'accounting';
        elseif ($user->can('orderteam')) $role = 'orderteam';
        elseif ($user->can('saler')) $role = 'saler';
        $form->role = $role;

        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'danh-don-hang-da-xac-nhan.xls';
            return $form->export($fileName);
        }

        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['order.created_at' => SORT_DESC])
                            ->asArray()
                            ->indexBy(function ($row) use(&$index){
                                return ++$index;
                             })
                            ->all();

                            $orderIds = ArrayHelper::getColumn($models, 'id');

        // Game method 
        $gameIds = ArrayHelper::getColumn($models, 'game_id');
        $gameIds = array_unique($gameIds);
        $gameTable = Game::tableName();
        $methodTable = GameMethod::tableName();
        $games = Game::find()
        ->innerJoin($methodTable, "{$methodTable}.id = {$gameTable}.method")
        ->where(["{$gameTable}.id" => $gameIds])
        ->select(["{$gameTable}.id as id", "{$methodTable}.title as title"])
        ->asArray()->all();
        $gameMethodMapping = ArrayHelper::map($games, 'id', 'title');
        // Get Complain
        $complains = OrderComplains::find()
        ->where(['in', 'order_id', $orderIds])             
        ->andWhere(['in', 'object_name', [OrderComplains::OBJECT_NAME_ADMIN, OrderComplains::OBJECT_NAME_SUPPLIER]])
        ->groupBy(['order_id'])
        ->select(['order_id', 'content'])
        ->all();
        $existStaffComplainIds = ArrayHelper::getColumn($complains, 'order_id');
        $contentComplainIds = ArrayHelper::map($complains, 'order_id', 'content');
        // Get Reseller
        $userIds = ArrayHelper::getColumn($models, 'customer_id');
        $users = User::find()
        ->where(['in', 'id', $userIds])
        ->indexBy('id')->all();

        // Get resellers 
        $resellers = UserReseller::find()
        ->where(['in', 'user_id', $userIds])
        ->indexBy('user_id')->all();

        // Get salers 
        $salerIds = ArrayHelper::getColumn($models, 'saler_id');
        $salers = User::find()
        ->where(['in', 'id', $salerIds])
        ->indexBy('id')->all();

        // order team
        $orderteamIds = ArrayHelper::getColumn($models, 'orderteam_id');
        $orderteams = User::find()
        ->where(['in', 'id', $orderteamIds])
        ->indexBy('id')->all();

        // Supplier
        $supplierIds = ArrayHelper::getColumn($models, 'supplier_id');
        $suppliers = User::find()->where(['in', 'id', $supplierIds])
        ->indexBy('id')->all();

        $data = [];
        foreach ($models as $model) {
            $user = $users[$model['customer_id']];
            $reseller = ArrayHelper::getValue($resellers, $user->id);
            $resellerLevel = $reseller ? $reseller->getLevelLabel() : '';

            $country = Country::findOne($user->country_code);
            $countryName = $country ? $country->country_name : '';
            
            $supplier = ArrayHelper::getValue($suppliers, $model['supplier_id']);
            $saler = ArrayHelper::getValue($salers, $model['saler_id']);
            $orderteam = ArrayHelper::getValue($orderteams, $model['orderteam_id']);

            // Promotion
            $promotion = $model['promotion_id'] ? Promotion::findOne($model['promotion_id']) : null;
            $item = [
                'id' => '#' . $model['id'],
                'customer_name' => $model['customer_name'],
                'reseller_level' => $resellerLevel,
                'country' => $countryName,
                'game_title' => $model['game_title'],
                'game_method' => ArrayHelper::getValue($gameMethodMapping, $model['game_id'], ''),
                'quantity' => $model['quantity'],
                'payment_method' => $model['payment_method'],
                'created_at' => $model['created_at'],
                'approved_at' => $model['approved_at'],
                'supplier_completed_at' => $model['supplier_completed_at'],
                'order_confirmed_at' => $model['order_confirmed_at'],
                'order_completed_time' => $model['order_completed_time'],
                'supplier_completed_time' => $model['supplier_completed_time'],
                'approved_time' => $model['approved_time'],
                'distributed_time' => $model['distributed_time'],
                'supplier_approved_time' => $model['supplier_approved_time'], 
                'supplier_pending_time' => $model['supplier_pending_time'],    
                'supplier_processing_time' => $model['supplier_processing_time'],  
                'supplier_confirmed_time' => $model['supplier_confirmed_time'], 
                'status' => $model['status'],  
                'is_wrong' => in_array($model['id'], $existStaffComplainIds) ? 'X' : '',   
                'wrong_information' => html_entity_decode(strip_tags(ArrayHelper::getValue($contentComplainIds, $model['id'], ''))),  
                'saler_name' => $saler ? $saler->getName() : '',   
                'orderteam_name' => $orderteam ? $orderteam->getName() : '',    
                'supplier_name' => $supplier ? $supplier->getName() : '',
                'price' => $model['price'],
                'total_price' => $model['price'] * $model['quantity'],
                'total_fee' => 0,
                'total_promotion' => 0,
                'total_paid' => $model['price'] * $model['quantity'],
                'total_received' => $model['price'] * $model['quantity'],
                'promotion_code' => $promotion ? $promotion->code : '',
                'exchange_rate' => $model['rate_usd'],
                'supplier_price' => $model['supplier_price'],
            ];
            $data[] = $item;
        }
        return $this->render('report', [
            'search' => $form,
            'models' => $data,
            'pages' => $pages,
        ]);
    }
}
