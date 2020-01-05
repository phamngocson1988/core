<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use supplier\models\Order;
use supplier\models\OrderFile;
use supplier\models\OrderComplainTemplate;
use supplier\models\OrderSupplier;
use supplier\forms\FetchOrderForm;
use yii\data\Pagination;
use yii\helpers\Url;
use supplier\behaviors\OrderLogBehavior;
use supplier\behaviors\OrderMailBehavior;
use supplier\behaviors\OrderSupplierBehavior;
use supplier\forms\TakeOrderSupplierForm;
use supplier\forms\RejectOrderSupplierForm;
use supplier\forms\StopOrderSupplierForm;

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

    public function actionWaiting()
    {
        $this->view->params['main_menu_active'] = 'order.waiting';
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
            'supplier_status' => OrderSupplier::STATUS_REQUEST
        ];
        $form = new FetchOrderForm($data);
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
            'order_id' => $id,
            'supplier_id' => Yii::$app->user->id

        ]);
        if ($form->validate()) {
            return $this->asJson(['status' => $form->approve()]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'error' => $error]);
    }

    public function actionReject($id)
    {
        $form = new RejectOrderSupplierForm([
            'order_id' => $id,
            'supplier_id' => Yii::$app->user->id

        ]);
        if ($form->validate()) {
            return $this->asJson(['status' => $form->reject()]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'error' => $error]);
    }

    public function actionStop($id)
    {
        $form = new StopOrderSupplierForm([
            'order_id' => $id,
            'supplier_id' => Yii::$app->user->id

        ]);
        if ($form->validate()) {
            return $this->asJson(['status' => $form->stop()]);
        }
        $errors = $form->getErrorSummary(false);
        $error = reset($errors);
        return $this->asJson(['status' => false, 'error' => $error]);
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
            'stopModel' => new \backend\forms\StopOrderForm(),
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

    public function actionMoveToProcessing($id)
    {
        $model = Order::findOne($id);
        if (!$model) return $this->asJson(['status' => false, 'error' => 'Đơn hàng không tồn tại']);
        if (!$model->isPendingOrder()) return $this->asJson(['status' => false, 'error' => 'Không thể chuyển trạng thái']);
        $request = Yii::$app->request;
        $model->setScenario(Order::SCENARIO_GO_PROCESSING);
        $model->status = Order::STATUS_PROCESSING;
        $model->on(Order::EVENT_AFTER_UPDATE, function($event) {
            $order = $event->sender;
            $order->touch('process_start_time');
            Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
            $order->attachBehavior('log', OrderLogBehavior::className());
            $order->attachBehavior('mail', OrderMailBehavior::className());
            $order->log("Moved to processing");
            $order->send(
                'admin_send_processing_order', 
                sprintf("[KingGems] - Processing Order - Order #%s", $order->id), [
                    'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
            ]);

        });

        return $this->renderJson($model->save());

    }

    public function actionMoveToCompleted($id)
    {
        $model = Order::findOne($id);
        if (!$model) return $this->asJson(['status' => false, 'error' => 'Đơn hàng không tồn tại']);
        if (!$model->isProcessingOrder()) return $this->asJson(['status' => false, 'error' => 'Không thể chuyển trạng thái']);
        $request = Yii::$app->request;
        $model->setScenario(Order::SCENARIO_GO_COMPLETED);
        $model->status = Order::STATUS_COMPLETED;
        $model->process_end_time = date('Y-m-d H:i:s');
        $model->process_duration_time = strtotime($model->process_end_time) - strtotime($model->process_start_time);
        $model->on(Order::EVENT_AFTER_UPDATE, function($event) {
            $order = $event->sender;
            Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
            $order->attachBehavior('log', OrderLogBehavior::className());
            $order->attachBehavior('mail', OrderMailBehavior::className());
            $order->log("Moved to completed");
            $order->send(
                'admin_send_complete_order', 
                sprintf("[KingGems] - Completed Order - Order #%s", $order->id), [
                    'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
            ]);
        });

        if ($model->save()) {
            $model->attachBehavior('supplier', OrderSupplierBehavior::className());
            $supplier = $order->supplier;
            if ($supplier) {
                $unit = $model->quantity - $model->doing_unit;
                $supplier->quantity = (float)$supplier->quantity + $unit;
                $supplier->total_price = $supplier->price * $supplier->quantity;
                $supplier->save();
            }
        }

        return $this->renderJson(true);
    }

    public function actionAddUnit($id)
    {
        $request = Yii::$app->request;
        $model = Order::findOne($id);
        if ($model) {
            $unit = $request->post('doing_unit', 0);
            $model->doing_unit += $unit;
            if ($model->doing_unit > $model->quantity) {
                return $this->renderJson(false, [], 'Bạn không thể nạp quá số gói game của đơn hàng này');
            } 
            if ($model->save(null, ['doing_unit'])) {
                $model->attachBehavior('supplier', OrderSupplierBehavior::className());
                $supplier = $order->supplier;
                if ($supplier) {
                  $supplier->quantity = (float)$supplier->quantity + $unit;
                  $supplier->total_price = $supplier->price * $supplier->quantity;
                  $supplier->save();
                }
                return $this->renderJson(true, ['total' => $model->doing_unit]);
            }
        } else {
            return $this->renderJson(false, [], 'Error');
        }
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


}
