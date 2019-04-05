<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\forms\FetchOrderForm;
use backend\forms\CreateOrderForm;
use backend\forms\EditOrderForm;
use backend\forms\CreateOrderItemForm;
use backend\forms\EditOrderItemForm;
use yii\data\Pagination;
use yii\helpers\Url;
use common\models\Order;
use common\models\OrderItems;
use backend\forms\UpdateOrderStatusPending;
use backend\forms\UpdateOrderStatusProcessing;
use backend\forms\TakenOrderForm;
use backend\forms\SendComplainForm;
use common\models\OrderComplainTemplate;

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
                        'actions' => ['view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'send-complain'],
                        'roles' => ['saler', 'handler', 'admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'move-to-pending', 'delete'],
                        'roles' => ['saler'],
                    ],
                    
                    [
                        'allow' => true,
                        'actions' => ['edit'],
                        'roles' => ['saler', 'handler'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['move-to-processing', 'taken', 'add-unit'],
                        'roles' => ['handler'],
                    ],
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
            'handler_id' => $request->get('handler_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
            'status' => $request->get('status'),
        ];
        $form = new FetchOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_DESC])
                            ->all();
        // Check the current user can take any order in the list
        $canTaken = false;
        if (Yii::$app->user->can('handler')) {
            $checkTaken = new FetchOrderForm([
                'handler_id' => Yii::$app->user->id,
                'status' => Order::STATUS_PENDING
            ]);
            $checkTakenCommand = $checkTaken->getCommand();
            $canTaken = !$checkTakenCommand->count();
        }

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
            'can_taken' => $canTaken,
        ]);
    }

    public function actionView($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = Order::findOne($id);
        $items = $order->items;
        $item = reset($items);
        return $this->render('view', [
            'order' => $order,
            'item' => $item,
            'back' => $request->get('ref', Url::to(['order/index']))
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = new CreateOrderForm();
        $item = new CreateOrderItemForm();
        if ($request->isPost) {
            $post = $request->post();
            if (!$order->load($post)) Yii::$app->session->setFlash('error', 'Order Error!');
            elseif (!$item->load($post)) {
                print_r($item->getErrors());die;
                Yii::$app->session->setFlash('error', 'Item Error!');
            }
            elseif (!$order->save()) Yii::$app->session->setFlash('error', 'Order Error!');
            $item->order_id = $order->id;
            if (!$item->save()) {
                print_r($item->getErrors());die;
                Yii::$app->session->setFlash('error', 'Item Error!');
            }
            $order->total_price = $item->getTotalPrice();
            $order->save();
            Yii::$app->session->setFlash('success', 'Success!');
            $ref = $request->get('ref', Url::to(['order/index']));
            return $this->redirect($ref);
        }

        return $this->render('create', [
            'order' => $order,
            'item' => $item,
            'back' => $request->get('ref', Url::to(['order/index']))
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'order.index';
        $request = Yii::$app->request;
        $order = EditOrderForm::findOne($id);
        $item = EditOrderItemForm::find()->where(['order_id' => $id])->one();
        switch ($order->status) {
            case EditOrderForm::STATUS_VERIFYING:
                $template = 'verifying';
                $updateStatusForm = new UpdateOrderStatusPending();
                $item->scenario = EditOrderItemForm::SCENARIO_VERIFYING;
                break;
            case EditOrderForm::STATUS_PENDING:
                $template = 'pending';
                $updateStatusForm = new UpdateOrderStatusProcessing();
                $item->scenario = EditOrderItemForm::SCENARIO_PENDING;
                break;
            case EditOrderForm::STATUS_PROCESSING:
                $template = 'processing';
                $updateStatusForm = new UpdateOrderStatusProcessing();
                $item->scenario = EditOrderItemForm::SCENARIO_PROCESSING;
                break;
            case EditOrderForm::STATUS_COMPLETED:
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
            if ($order->isVerifyingOrder()) {
                if (!$order->load($post)) Yii::$app->session->setFlash('error', 'Load Order Error!');
                elseif (!$order->save()) Yii::$app->session->setFlash('error', 'Save Order Error!');
            }
            if (!$item->load($post)) Yii::$app->session->setFlash('error', 'Load Item Error!');
            if (!$item->save()) Yii::$app->session->setFlash('error', 'Save Item Error!');
            Yii::$app->session->setFlash('success', 'Success!');
            $ref = $request->get('ref', Url::to(['order/index']));
            return $this->redirect($ref);
        }

        // template of complain
        $templateList = OrderComplainTemplate::find()->all();
        return $this->render($template, [
            'order' => $order,
            'item' => $item,
            'updateStatusForm' => $updateStatusForm,
            'back' => $request->get('ref', Url::to(['order/index'])),
            'template_list' => $templateList,
        ]);
    }

    public function actionMoveToPending()
    {
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $form = new UpdateOrderStatusPending();
            if ($form->load($request->post()) && $form->save()) {
                return $this->renderJson(true, []);
            } else {
                return $this->renderJson(false, [], $form->getErrorSummary(true));
            }
        }
    }

    public function actionMoveToProcessing()
    {
        $request = Yii::$app->request;
        if ($request->isPost && $request->isAjax) {
            $form = new UpdateOrderStatusProcessing();
            if ($form->load($request->post()) && $form->save()) {
                return $this->renderJson(true, []);
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
                'user_id' => Yii::$app->user->id,
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
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionAddUnit($id)
    {
        $request = Yii::$app->request;
        $model = OrderItems::findOne($id);
        if ($model) {
            $unit = $request->post('doing_unit', 0);
            $model->doing_unit += $unit;
            $model->save();
            return $this->renderJson(true, ['total' => $model->doing_unit]);
        } else {
            return $this->renderJson(false, [], 'Error');
        }
    }
}
