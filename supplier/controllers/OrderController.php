<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use supplier\models\Order;
use supplier\forms\FetchOrderForm;
use yii\data\Pagination;
use yii\helpers\Url;

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
            'supplier_accept' => 'Y'
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
            'supplier_accept' => 'N'
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
        $order = Order::findOne($id);
        if (!$order) throw new Exception("Not found", 1);
        $order->setScenario(Order::SCENARIO_ACCEPT);
        $order->supplier_accept = 'Y';
        $order->supplier_accept_time = date('Y-m-d H:i:s');
        return $this->asJson(['status' => $order->save()]);
    }

    public function actionReject($id)
    {
        $order = Order::findOne($id);
        if (!$order) throw new Exception("Not found", 1);
        $order->setScenario(Order::SCENARIO_REJECT);
        $order->on(Order::EVENT_AFTER_UPDATE, function($event) {
            $model = $event->sender;
            // send mail to admin
        });
        $order->supplier_id = null;
        return $this->asJson(['status' => $order->save()]);
    }
}
