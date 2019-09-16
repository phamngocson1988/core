<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\forms\FetchOrderForm;
use backend\forms\FetchMyOrderForm;
use backend\forms\FetchNewPendingOrderForm;
use backend\forms\CreateOrderForm;
use backend\forms\EditOrderForm;
use backend\forms\EditOrderItemForm;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\models\Order;
use backend\models\User;
use backend\models\Game;
use backend\models\OrderFile;
use backend\forms\UpdateOrderStatusProcessing;
use backend\forms\AssignManageOrder;
use backend\forms\TakenOrderForm;
use backend\forms\SendComplainForm;
use common\models\OrderComplainTemplate;
use backend\forms\MyCustomerReportForm;
use backend\forms\CancelOrderForm;


use backend\events\OrderEventHandler;
use backend\models\OrderComplains;

class OrderController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view', 'my-customer-report', 'my-customer-orders'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'send-complain'],
                        'roles' => ['saler', 'orderteam', 'admin', 'accounting'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'new-pending-order'],
                        'roles' => ['saler'],
                    ],
                    
                    [
                        'allow' => true,
                        'actions' => ['edit', 'my-order', 'pending', 'processing', 'approve', 'disapprove'],
                        'roles' => ['saler', 'orderteam', 'accounting'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['move-to-processing', 'taken', 'add-unit', 'new-pending-order', 'add-evidence-image', 'remove-evidence-image'],
                        'roles' => ['orderteam'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['assign'],
                        'roles' => ['orderteam_manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['verifying', 'delete', 'move-to-pending', 'new-verifying-order'],
                        'roles' => ['accounting']
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['delete'],
                ],
            ],
        ];
    }

    /**
     * Show the list of orders
     */
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $data = [
            'q' => $request->get('q'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'provider_id' => $request->get('provider_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => $request->get('status'),
        ];
        $form = new FetchOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_DESC])
                            ->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionMyOrder()
    {
        $this->view->params['main_menu_active'] = 'order.mine';
        $request = Yii::$app->request;
        $data = [
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
            'status' => $request->get('status'),
        ];
        $form = new FetchMyOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_DESC])
                            ->all();

        return $this->render('my-order', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionMyCustomerReport()
    {
        $this->view->params['main_menu_active'] = 'order.report';
        $request = Yii::$app->request;
        $data = [
            'start_date' => $request->get('start_date', date('Y-m-01')),
            'end_date' => $request->get('end_date', date('Y-m-t')),
            'user_id' => Yii::$app->user->id,
        ];
        $form = new MyCustomerReportForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_DESC])
                            ->all();

        return $this->render('my-customer-report', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionMyCustomerOrders()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $data = [
                'customer_id' => $request->get('customer_id'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'status' => $request->get('status'),
            ];
            $form = new FetchOrderForm($data);
            $command = $form->getCommand();
            $models = $command->all();
            return $this->renderPartial('my-customer-orders', [
                'models' => $models,
            ]);
        }
    }

    public function actionNewPendingOrder()
    {
        $this->view->params['main_menu_active'] = 'order.new';
        $request = Yii::$app->request;
        $data = [
            'q' => $request->get('q'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'game_id' => $request->get('game_id'),
        ];
        $form = new FetchNewPendingOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_ASC])
                            ->all();

        return $this->render('new-pending-order', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionNewVerifyingOrder()
    {
        $this->view->params['main_menu_active'] = 'order.new-verifying';
        $request = Yii::$app->request;
        $command = Order::find()->where(['status' => Order::STATUS_VERIFYING]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_ASC])
                            ->all();

        return $this->render('new-verifying-order', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = new Order(['scenario' => Order::SCENARIO_CREATE]);
        if ($order->load($request->post()) && $order->validate()) {
            $customer = User::findOne($order->customer_id);
            $game = Game::findOne($order->game_id);
            $order->generateAuthKey();
            $order->status = Order::STATUS_VERIFYING;
            $order->saler_id = Yii::$app->user->id;
            $order->customer_name = $customer->name;
            $order->customer_email = $customer->email;
            $order->customer_phone = $customer->phone;
            $order->game_title = $game->title;
            $order->unit_name = $game->unit_name;
            $order->total_unit = $game->pack * (float)$order->quantity;
            $order->sub_total_unit = $game->pack * (float)$order->quantity;
            $order->sub_total_price = $game->price * (float)$order->quantity;
            $order->total_price = $game->price * (float)$order->quantity;
            if ($order->save(false)) {
                Yii::$app->session->setFlash('success', 'Tạo mới đơn hàng thành công.');
                return $this->redirect(['order/index']);
            }
        }

        return $this->render('create', [
            'order' => $order,
            'back' => $request->get('ref', Url::to(['order/index']))
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


    public function actionVerifying($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        // if (!Yii::$app->user->can('edit_order', ['order' => $order])) throw new \yii\web\ForbiddenHttpException('Bạn không có quyền truy cập chức năng này');
        $order->setScenario(Order::SCENARIO_VERIFYING);
        if ($order->load($request->post()) && $order->save()) {
            Yii::$app->session->setFlash('success', 'Success!');
        }
        return $this->render('verifying', [
            'order' => $order,
        ]);
    }

    public function actionPending($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        if (!Yii::$app->user->can('edit_order', ['order' => $order])) throw new \yii\web\ForbiddenHttpException('Bạn không có quyền truy cập chức năng này');
        $order->setScenario(Order::SCENARIO_PENDING);
        if ($order->load($request->post()) && $order->save()) {
            Yii::$app->session->setFlash('success', 'Success!');
        }
        $updateStatusForm = new UpdateOrderStatusProcessing();
        $templateList = OrderComplainTemplate::find()->all();

        return $this->render('pending', [
            'order' => $order,
            'updateStatusForm' => $updateStatusForm,
            'template_list' => $templateList
        ]);
    }

    public function actionProcessing($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        if (!Yii::$app->user->can('edit_order', ['order' => $order])) throw new \yii\web\ForbiddenHttpException('Bạn không có quyền truy cập chức năng này');
        $order->setScenario(Order::SCENARIO_PROCESSING);
        if ($order->load($request->post()) && $order->save()) {
            Yii::$app->session->setFlash('success', 'Success!');
        }
        $templateList = OrderComplainTemplate::find()->all();
        
        return $this->render('processing', [
            'order' => $order,
            'template_list' => $templateList
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        // $item = EditOrderItemForm::find()->where(['order_id' => $id])->one();
        switch ($order->status) {
            case Order::STATUS_VERIFYING:
                $template = 'verifying';
                break;
            case Order::STATUS_PENDING:
                $template = 'pending';
                $updateStatusForm = new UpdateOrderStatusProcessing();
                // $item->scenario = EditOrderItemForm::SCENARIO_PENDING;
                break;
            case Order::STATUS_PROCESSING:
                $template = 'processing';
                $updateStatusForm = new UpdateOrderStatusProcessing();
                // $item->scenario = EditOrderItemForm::SCENARIO_PROCESSING;
                break;
            case Order::STATUS_COMPLETED:
                $template = 'view';
                $updateStatusForm = new UpdateOrderStatusProcessing();
                break;
            
            default:
                $template = 'view';
                $updateStatusForm = null;
                break;
        }

        if ($request->isPost) {
            $post = $request->post();
            // if ($order->isVerifyingOrder()) {
            //     if (!$order->load($post)) Yii::$app->session->setFlash('error', 'Load Order Error!');
            //     elseif (!$order->save()) Yii::$app->session->setFlash('error', 'Save Order Error!');
            // }
            // if (!$item->load($post)) Yii::$app->session->setFlash('error', 'Load Item Error!');
            // if (!$item->save()) Yii::$app->session->setFlash('error', 'Save Item Error!');
            Yii::$app->session->setFlash('success', 'Success!');
            $ref = $request->get('ref', Url::to(['order/index']));
            return $this->redirect($ref);
        }

        // template of complain
        $templateList = OrderComplainTemplate::find()->all();
        return $this->render($template, [
            'order' => $order,
            // 'item' => $item,
            'updateStatusForm' => $updateStatusForm,
            'back' => $request->get('ref', Url::to(['order/index'])),
            'template_list' => $templateList,
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
            $user = Yii::$app->user->getIdentity();
            $complain = new OrderComplains();
            $complain->order_id = $order->id;
            $complain->content = sprintf("%s (#%s) move order to pending. The payment data is %s", $user->name, $user->id, $order->payment_data);
            $complain->save();
        });
        if (!$model->auth_key) $model->generateAuthKey();
        $model->payment_type = 'offline';
        $model->status = Order::STATUS_PENDING;
        if ($model->load($request->post()) && $model->save()) {
            return $this->renderJson(true, ['next' => Url::to(['order/index'])]);
        } else {
            return $this->asJson(['status' => true, 'error' => $model->getErrorSummary(false)]);
        }
    }

    public function actionMoveToProcessing()
    {
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $form = new UpdateOrderStatusProcessing();
            if ($form->load($request->post()) && $form->save()) {
                return $this->renderJson(true, ['next' => Url::to(['order/processing', 'id' => $form->id])]);
            } else {
                return $this->renderJson(false, [], $form->getErrorSummary(true));
            }
        }
    }

    public function actionTaken($id)
    {
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $form = new TakenOrderForm([
                'order_id' => $id,
            ]);
            if ($form->validate() && $form->taken()) {
                return $this->renderJson(true, []);
            } else {
                return $this->renderJson(false, [], $form->getErrorSummary(true));
            }
        }
    }

    public function actionSendComplain()
    {
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $form = new SendComplainForm([
                'order_id' => $request->post('order_id'),
                'template_id' => $request->post('template_id')
            ]);
            if ($form->send()) {
                return $this->renderJson(true, []);
            } else {
                return $this->renderJson(false, [], $form->getErrorSummary(true));
            }
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = Order::findOne($id);
        if ($model && $model->delete()) {
            return $this->renderJson(true, ['url' => Url::to(['order/index'])]);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionAddUnit($id)
    {
        $request = Yii::$app->request;
        $model = Order::findOne($id);
        if ($model) {
            $unit = $request->post('doing_unit', 0);
            $model->doing_unit += $unit;
            if ($model->doing_unit > $model->total_unit) return $this->renderJson(false, [], 'Bạn không thể nạp quá số game của đơn hàng này');
            $model->save();
            return $this->renderJson(true, ['total' => $model->doing_unit]);
        } else {
            return $this->renderJson(false, [], 'Error');
        }
    }

    public function actionAssign($id)
    {
        $request = Yii::$app->request;
        $userId = $request->post('user_id');
        $assignForm = new AssignManageOrder([
            'user_id' => $userId,
            'order_id' => $id
        ]);
        if ($assignForm->save()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $assignForm->getErrorSummary(true));
        }
    }

    public function actionApprove($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $order = Order::findOne($id);
            if ($order->isPendingOrder()) {
                $order->status = Order::STATUS_DELETED;
                $order->on(Order::EVENT_AFTER_UPDATE, [OrderEventHandler::className(), 'sendMailDeleteOrder']);
                $order->on(Order::EVENT_AFTER_UPDATE, [OrderEventHandler::className(), 'removeCommission']);
                $order->on(Order::EVENT_AFTER_UPDATE, [OrderEventHandler::className(), 'refundOrder']);
                return $this->renderJson($order->save(), ['view_url' => Url::to(['order/view', 'id' => $id])], []);
            }
        }
        return $this->renderJson(false, null, ['error' => 'Không thể cancel đơn hàng']);
    }

    public function actionDisapprove($id)
    {
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $order = Order::findOne($id);
            $order->request_cancel = 0;
            $order->save();
            $form = new SendComplainForm([
                'order_id' => $id,
                'template_id' => $request->post('template_id')
            ]);
            if ($form->send()) {
                return $this->renderJson(true, []);
            } else {
                return $this->renderJson(false, [], $form->getErrorSummary(true));
            }
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
}