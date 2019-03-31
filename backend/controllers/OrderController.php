<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchOrderForm;
use backend\forms\CreateOrderForm;
use backend\forms\CreateOrderItemForm;
use yii\data\Pagination;
use yii\helpers\Url;
use common\models\Order;
use common\models\OrderItems;
use backend\forms\UpdateOrderStatusPending;
use backend\forms\UpdateOrderStatusProcessing;

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
                        'actions' => ['index'],
                        'roles' => ['saler', 'handler', 'admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['saler'],
                    ],
                    
                    [
                        'allow' => true,
                        'actions' => ['edit'],
                        'roles' => ['saler', 'handler'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['move-to-pending'],
                        'roles' => ['saler'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['move-to-processing'],
                        'roles' => ['handler'],
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
            'handler_id' => $request->get('handler_id'),
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'status' => $request->get('status'),
        ];
        $form = new FetchOrderForm($data);
        // print_r($data);die;
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
        $order = CreateOrderForm::findOne($id);
        $item = CreateOrderItemForm::find()->where(['order_id' => $id])->one();
        if ($request->isPost) {
            $post = $request->post();
            if (!$order->load($post)) Yii::$app->session->setFlash('error', 'Order Error!');
            elseif (!$item->load($post)) Yii::$app->session->setFlash('error', 'Item Error!');
            elseif (!$order->save()) Yii::$app->session->setFlash('error', 'Order Error!');
            if (!$item->save()) Yii::$app->session->setFlash('error', 'Item Error!');
            $order->total_price = $item->getTotalPrice();
            $order->save();
            Yii::$app->session->setFlash('success', 'Success!');
            $ref = $request->get('ref', Url::to(['order/index']));
            return $this->redirect($ref);
        }
        switch ($order->status) {
            case CreateOrderForm::STATUS_VERIFYING:
                $template = 'verifying';
                $updateStatusForm = new UpdateOrderStatusPending();
                break;
            case CreateOrderForm::STATUS_PENDING:
                $template = 'pending';
                $updateStatusForm = new UpdateOrderStatusProcessing();
                break;
            case CreateOrderForm::STATUS_PROCESSING:
                $template = 'processing';
                $updateStatusForm = new UpdateOrderStatusProcessing();
                break;
            case CreateOrderForm::STATUS_COMPLETED:
                $template = 'completed';
                $updateStatusForm = new UpdateOrderStatusProcessing();
                break;
            
            default:
                $template = 'completed';
                $updateStatusForm = null;
                break;
        }
        return $this->render($template, [
            'order' => $order,
            'item' => $item,
            'updateStatusForm' => $updateStatusForm,
            'back' => $request->get('ref', Url::to(['order/index']))
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
}
