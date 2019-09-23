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
use backend\models\Order;
use backend\models\User;
use backend\models\Game;
use backend\models\OrderFile;
use backend\forms\UpdateOrderStatusProcessing;
use backend\forms\AssignManageOrder;
use backend\forms\SendComplainForm;
use common\models\OrderComplainTemplate;
use backend\forms\MyCustomerReportForm;
use backend\forms\CancelOrderForm;
use backend\forms\FetchOrderByFeedback;


use backend\events\OrderEventHandler;
use backend\models\OrderComplains;

class OrderController extends Controller
{
    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::className(),
    //             'rules' => [
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['view', 'my-customer-report', 'my-customer-orders'],
    //                     'roles' => ['@'],
    //                 ],
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['index', 'send-complain'],
    //                     'roles' => ['saler', 'orderteam', 'admin', 'accounting'],
    //                 ],
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['create', 'new-pending-order'],
    //                     'roles' => ['saler'],
    //                 ],
                    
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['edit', 'my-order', 'pending', 'processing', 'approve', 'disapprove'],
    //                     'roles' => ['saler', 'orderteam', 'accounting'],
    //                 ],
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['move-to-processing', 'taken', 'add-unit', 'new-pending-order', 'add-evidence-image', 'remove-evidence-image'],
    //                     'roles' => ['orderteam'],
    //                 ],
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['assign'],
    //                     'roles' => ['orderteam_manager'],
    //                 ],
    //                 [
    //                     'allow' => true,
    //                     'actions' => ['verifying', 'delete', 'move-to-pending', 'new-verifying-order'],
    //                     'roles' => ['accounting']
    //                 ]
    //             ],
    //         ],
    //         'verbs' => [
    //             'class' => VerbFilter::className(),
    //             'actions' => [
    //                 'delete' => ['delete'],
    //             ],
    //         ],
    //     ];
    // }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // [
                    //     'allow' => true,
                    //     'actions' => ['new-verifying-order', 'verifying', 'move-to-pending', 'delete'],
                    //     'roles' => ['accounting'],
                    // ],
                    // [
                    //     'allow' => true,
                    //     'actions' => ['pending', 'processing', 'new-pending-order', 'move-to-processing', 'taken', 'assign', 'add-unit', 'approve', 'disapprove', 'add-evidence-image', 'remove-evidence-image'],
                    //     'roles' => ['orderteam'],
                    // ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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

    public function actionNewPendingOrder()
    {
        $this->view->params['main_menu_active'] = 'order.new';
        $request = Yii::$app->request;
        $command = Order::find();
        $command->where(['status' => Order::STATUS_PENDING, 'orderteam_id' => null]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_ASC])
                            ->all();

        return $this->render('new-pending-order', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionPendingOrder()
    {
        $this->view->params['main_menu_active'] = 'order.pending';
        $request = Yii::$app->request;
        $command = Order::find();
        $command->where(['status' => Order::STATUS_PENDING]);
        $command->andWhere(['IS NOT', 'orderteam_id', null]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_DESC])
                            ->all();

        return $this->render('pending-order', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCancellingOrder()
    {
        $this->view->params['main_menu_active'] = 'order.cancelling';
        $request = Yii::$app->request;
        $command = Order::find();
        $command->where(['<>', 'status', Order::STATUS_DELETED]);
        $command->andWhere(['request_cancel' => 1]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_ASC])
                            ->all();

        return $this->render('cancelling-order', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionProcessingOrder()
    {
        $this->view->params['main_menu_active'] = 'order.processing';
        $request = Yii::$app->request;
        $command = Order::find();
        $command->where(['status' => Order::STATUS_PROCESSING]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_ASC])
                            ->all();

        return $this->render('processing-order', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCompletedOrder()
    {
        $this->view->params['main_menu_active'] = 'order.completed';
        $request = Yii::$app->request;
        $command = Order::find();
        $command->where(['status' => Order::STATUS_COMPLETED]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_ASC])
                            ->all();

        return $this->render('completed-order', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCancelledOrder()
    {
        $this->view->params['main_menu_active'] = 'order.cancelled';
        $request = Yii::$app->request;
        $command = Order::find();
        $command->where(['status' => Order::STATUS_DELETED]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_ASC])
                            ->all();

        return $this->render('cancelled-order', [
            'models' => $models,
            'pages' => $pages,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionDislikeOrder()
    {
        $this->view->params['main_menu_active'] = 'order.dislike';
        $request = Yii::$app->request;
        $command = Order::find()->where(['rating' => -1]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_ASC])
                            ->all();

        return $this->render('dislike-order', [
            'models' => $models,
            'pages' => $pages,
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
                            ->orderBy(['updated_at' => SORT_ASC])
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

    public function actionVerifying($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = Order::findOne($id);
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

    public function actionMoveToPending($id)
    {
        $model = Order::findOne($id);
        if (!$model) return $this->asJson(['status' => false, 'error' => 'Đơn hàng không tồn tại']);
        if (!$model->isVerifyingOrder()) return $this->asJson(['status' => false, 'error' => 'Không thể chuyển trạng thái']);
        $request = Yii::$app->request;
        $model->setScenario(Order::SCENARIO_GO_PENDING);
        $model->on(Order::EVENT_AFTER_UPDATE, function ($event) {
            $order = $event->sender;
            $adminEmail = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
            if ($adminEmail) {
                Yii::$app->mailer->compose('admin_send_pending_order', [
                    'order' => $order,
                    'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
                ])
                ->setTo($order->customer_email)
                ->setFrom([$adminEmail => Yii::$app->name . ' Administrator'])
                ->setSubject(sprintf("Order confirmation - %s", $order->id))
                ->setTextBody("Your order is moved to pending status")
                ->send();
            }
        });
        $model->on(Order::EVENT_AFTER_UPDATE, function ($event) {
            // Save a complain
            $order = $event->sender;
            $user = Yii::$app->user->getIdentity();
            $complain = new OrderComplains();
            $complain->order_id = $order->id;
            $complain->content = sprintf("%s move order to pending. The payment data is %s", $user->name, $order->payment_data);
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

    public function actionMoveToProcessing($id)
    {
        // $request = Yii::$app->request;
        // if ($request->isPost && $request->isAjax) {
        //     $form = new UpdateOrderStatusProcessing();
        //     if ($form->load($request->post()) && $form->save()) {
        //         return $this->renderJson(true, ['next' => Url::to(['order/processing', 'id' => $form->id])]);
        //     } else {
        //         return $this->renderJson(false, [], $form->getErrorSummary(true));
        //     }
        // }
        $model = Order::findOne($id);
        if (!$model) return $this->asJson(['status' => false, 'error' => 'Đơn hàng không tồn tại']);
        if (!$model->isPendingOrder()) return $this->asJson(['status' => false, 'error' => 'Không thể chuyển trạng thái']);
        $request = Yii::$app->request;
        $model->setScenario(Order::SCENARIO_GO_PROCESSING);
        $model->status = Order::STATUS_PROCESSING;
        $model->doing_unit = $model->total_unit;
        $model->process_end_time = date('Y-m-d H:i:s');
        $model->process_duration_time = strtotime($model->process_end_time) - strtotime($model->process_start_time);
        $model->on(Order::EVENT_AFTER_UPDATE, function($event) {
            $order = $event->sender;
            $settings = Yii::$app->settings;
            $adminEmail = $settings->get('ApplicationSettingForm', 'customer_service_email');
            $frontend = Yii::$app->params['frontend_url'];
            Yii::$app->urlManagerFrontend->setHostInfo($frontend);
            Yii::$app->mailer->compose('admin_send_complete_order', [
                'order' => $order,
                'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
            ])
            ->setTo($order->customer_email)
            ->setFrom([$adminEmail => Yii::$app->name])
            ->setSubject(sprintf("[KingGems] - Completed Order - Order #%s", $order->id))
            ->setTextBody("Your order #<?=$this->id;?> has been completed now. Please review it")
            ->send();
        });
        $model->on(Order::EVENT_AFTER_UPDATE, function($event) {
            $order = $event->sender;
            $user = Yii::$app->user->getIdentity();
            $complain = new OrderComplains();
            $complain->order_id = $order->id;
            $complain->content = sprintf("%s moved the order to processing.", $user->name);
            $complain->save();
        });
        return $this->renderJson($model->save());
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
        $order->attachBehavior('assign', \backend\behaviors\OrderBehavior::className());
        if ($order->assignOrderTeam($userId)) {
            // Yii::$app->session->setFlash('success', "You have assign order #$id successfully.");
            return $this->asJson(['status' => true]);
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
            Yii::$app->session->setFlash('success', "You have deleted order #$id successfully.");
            return $this->renderJson(true, ['url' => Url::to(['order/index'])]);
        } else {
            $message = ($model) ? reset($form->getErrorSummary(false)) : "Order #$id not found";
            Yii::$app->session->setFlash('error', $message);
            return $this->renderJson(false, [], $message);
        }
    }

    public function actionAddUnit($id)
    {
        $request = Yii::$app->request;
        $model = Order::findOne($id);
        if ($model) {
            $unit = $request->post('doing_unit', 0);
            $model->doing_unit += $unit;
            if ($model->doing_unit > $model->quantity) return $this->renderJson(false, [], 'Bạn không thể nạp quá số gói game của đơn hàng này');
            $model->save();
            return $this->renderJson(true, ['total' => $model->doing_unit]);
        } else {
            return $this->renderJson(false, [], 'Error');
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
            $model = Order::findOne($id);
            $model->on(Order::EVENT_AFTER_UPDATE, function ($event) {
                // Save a complain
                $order = $event->sender;
                $user = Yii::$app->user->getIdentity();
                $complain = new OrderComplains();
                $complain->order_id = $order->id;
                $complain->content = sprintf("Your order is cancelled by %s", $user->name);
                $complain->save();
            });
            $model->request_cancel = 0;
            return $this->renderJson($model->save());
            // $order->save();
            // $form = new SendComplainForm([
            //     'order_id' => $id,
            //     'template_id' => $request->post('template_id')
            // ]);
            // if ($form->send()) {
            //     return $this->renderJson(true, []);
            // } else {
            //     return $this->renderJson(false, [], $form->getErrorSummary(true));
            // }
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
