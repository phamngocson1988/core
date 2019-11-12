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
use common\models\OrderComplainTemplate;
use backend\forms\MyCustomerReportForm;
use backend\forms\CancelOrderForm;
use backend\forms\FetchOrderByFeedback;


use backend\events\OrderEventHandler;
use backend\models\OrderComplains;
use backend\behaviors\OrderLogBehavior;
use backend\behaviors\OrderMailBehavior;

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
            'provider_id' => $request->get('provider_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => $request->get('status', [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED,
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
            'provider_id' => $request->get('provider_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'game_id' => $request->get('game_id'),
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
            'q' => $request->get('q'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'provider_id' => $request->get('provider_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => Order::STATUS_PENDING,
        ];
        $form = new FetchOrderForm($data);
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
            'q' => $request->get('q'),
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'provider_id' => $request->get('provider_id'),
            'orderteam_id' => $request->get('orderteam_id'),
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

    public function actionCompleted()
    {
        $this->view->params['main_menu_active'] = 'order.completed';
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
            'status' => Order::STATUS_COMPLETED,
        ];
        $form = new FetchOrderForm($data);
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

    public function actionConfirmed()
    {
        $this->view->params['main_menu_active'] = 'order.confirmed';
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
            'status' => Order::STATUS_CONFIRMED,
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
            'customer_id' => $request->get('customer_id'),
            'saler_id' => $request->get('saler_id'),
            'provider_id' => $request->get('provider_id'),
            'orderteam_id' => $request->get('orderteam_id'),
            'game_id' => $request->get('game_id'),
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
            'provider_id' => $request->get('provider_id'),
            'orderteam_id' => $request->get('orderteam_id'),
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

    public function actionMoveToPending($id)
    {
        $model = Order::findOne($id);
        if (!$model) return $this->asJson(['status' => false, 'error' => 'Đơn hàng không tồn tại']);
        if (!$model->isVerifyingOrder()) return $this->asJson(['status' => false, 'error' => 'Không thể chuyển trạng thái']);
        $request = Yii::$app->request;
        $model->setScenario(Order::SCENARIO_GO_PENDING);
        $model->on(Order::EVENT_AFTER_UPDATE, function ($event) {
            $order = $event->sender;
            Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
            $order->attachBehavior('mail', OrderMailBehavior::className());
            $order->attachBehavior('log', OrderLogBehavior::className());
            $order->send(
                'admin_send_pending_order', 
                sprintf("Order confirmation - %s", $order->id), [
                    'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
            ]);
            $order->log(sprintf("Moved to pending with payment_id: %s", $order->payment_id));

            // $adminEmail = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
            // if ($adminEmail) {
            //     Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
            //     Yii::$app->mailer->compose('admin_send_pending_order', [
            //         'order' => $order,
            //         'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
            //     ])
            //     ->setTo($order->customer_email)
            //     ->setFrom([$adminEmail => Yii::$app->name . ' Administrator'])
            //     ->setSubject(sprintf("Order confirmation - %s", $order->id))
            //     ->setTextBody("Your order is moved to pending status")
            //     ->send();
            // }
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
        $model = Order::findOne($id);
        if (!$model) return $this->asJson(['status' => false, 'error' => 'Đơn hàng không tồn tại']);
        if (!$model->isPendingOrder()) return $this->asJson(['status' => false, 'error' => 'Không thể chuyển trạng thái']);
        $request = Yii::$app->request;
        $model->setScenario(Order::SCENARIO_GO_PROCESSING);
        $model->status = Order::STATUS_PROCESSING;
        $model->process_start_time = date('Y-m-d H:i:s');
        $model->on(Order::EVENT_AFTER_UPDATE, function($event) {
            $order = $event->sender;
            Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
            $order->attachBehavior('log', OrderLogBehavior::className());
            $order->attachBehavior('mail', OrderMailBehavior::className());
            $order->log("Moved to processing");
            $order->send(
                'admin_send_processing_order', 
                sprintf("[KingGems] - Processing Order - Order #%s", $order->id), [
                    'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
            ]);

            // $settings = Yii::$app->settings;
            // $adminEmail = $settings->get('ApplicationSettingForm', 'customer_service_email');
            // $frontend = Yii::$app->params['frontend_url'];
            // Yii::$app->urlManagerFrontend->setHostInfo($frontend);
            // Yii::$app->mailer->compose('admin_send_processing_order', [
            //     'order' => $order,
            //     'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
            // ])
            // ->setTo($order->customer_email)
            // ->setFrom([$adminEmail => Yii::$app->name])
            // ->setSubject(sprintf("[KingGems] - Processing Order - Order #%s", $order->id))
            // ->setTextBody("Your order " . $order->id . " has been processed now. Please review it")
            // ->send()
            // ;
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

            // $settings = Yii::$app->settings;
            // $adminEmail = $settings->get('ApplicationSettingForm', 'customer_service_email');
            // $frontend = Yii::$app->params['frontend_url'];
            // Yii::$app->urlManagerFrontend->setHostInfo($frontend);
            // Yii::$app->mailer->compose('admin_send_complete_order', [
            //     'order' => $order,
            //     'order_link' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $order->id], true),
            // ])
            // ->setTo($order->customer_email)
            // ->setFrom([$adminEmail => Yii::$app->name])
            // ->setSubject(sprintf("[KingGems] - Completed Order - Order #%s", $order->id))
            // ->setTextBody("Your order " . $order->id . " has been completed now. Please review it")
            // ->send();
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

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = Order::findOne($id);
        $model->attachBehavior('log', OrderLogBehavior::className());
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
            $order->on(Order::EVENT_AFTER_UPDATE, [OrderEventHandler::className(), 'sendMailDeleteOrder']);
            $order->on(Order::EVENT_AFTER_UPDATE, function($event) {
                $model = $event->sender;
                $model->attachBehavior('log', OrderLogBehavior::className());
                $model->log(sprintf("Approved to be cancelled when status is %s", $model->status));
            });
            if ($order->isPendingOrder() || $order->isProcessingOrder()) {
                $order->status = Order::STATUS_CANCELLED;
                $order->on(Order::EVENT_AFTER_UPDATE, [OrderEventHandler::className(), 'removeCommission']);
                $order->on(Order::EVENT_AFTER_UPDATE, [OrderEventHandler::className(), 'refundOrder']);
                return $this->renderJson($order->save(), ['view_url' => Url::to(['order/view', 'id' => $id])], []);
            } elseif ($order->isVerifyingOrder()) {
                $order->status = Order::STATUS_CANCELLED;
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
                $complain = new OrderComplains();
                $complain->order_id = $order->id;
                $complain->content = sprintf("Your request is cancelled by admin");
                $complain->save();
            });
            $model->on(Order::EVENT_AFTER_UPDATE, function($event) {
                $order = $event->sender;
                $order->attachBehavior('log', OrderLogBehavior::className());
                $order->log(sprintf("Disapproved to be cancelled when status is %s", $order->status));
            });
            $model->request_cancel = 0;
            return $this->renderJson($model->save());
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
        $model = new \backend\forms\StopOrderForm();
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->validate()) {
                $model->stop();
                return $this->renderJson(true);
            } else {
                $this->renderJson(false, $model->getErrors());
            }
        }

    }

    public function actionSendMailVerifyingOrder($id)
    {
        $order = Order::findOne($id);
        if ($order) {
            $order->attachBehavior('mail', OrderMailBehavior::className());
            $order->send('admin_notify_order_failure', '[KINGGEMS]-FAILED TRANSACTION');
        }
        return $this->renderJson(true);
    }
}
