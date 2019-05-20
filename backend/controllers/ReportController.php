<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\forms\FetchOrderForm;
use backend\forms\ReportByGameForm;
use backend\forms\ReportByUserForm;
use backend\forms\ReportByTransactionForm;
use backend\forms\ReportByBalanceForm;
use backend\forms\GetUserWalletBalance;
use yii\data\Pagination;
use common\models\Order;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

class ReportController extends Controller
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
                        'roles' => ['accounting'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Show the list of orders
     */
    // public function actionIndex()
    // {
    //     $this->view->params['main_menu_active'] = 'report.index';
    //     $request = Yii::$app->request;
    //     $data = [
    //         'q' => $request->get('q'),
    //         'customer_id' => $request->get('customer_id'),
    //         'saler_id' => $request->get('saler_id'),
    //         'handler_id' => $request->get('handler_id'),
    //         'game_id' => $request->get('game_id'),
    //         'start_date' => $request->get('start_date', date('Y-m-01')),
    //         'end_date' => $request->get('end_date', date('Y-m-t')),
    //         'status' => $request->get('status'),
    //     ];
    //     $form = new FetchOrderForm($data);
    //     $command = $form->getCommand();
    //     $pages = new Pagination(['totalCount' => $command->count()]);
    //     $models = $command->offset($pages->offset)
    //                         ->limit($pages->limit)
    //                         ->orderBy(['updated_at' => SORT_DESC])
    //                         ->all();
    //     return $this->render('index', [
    //         'models' => $models,
    //         'pages' => $pages,
    //         'search' => $form,
    //     ]);
    // }

    // public function actionView($id)
    // {
    //     $this->view->params['main_menu_active'] = 'report.index';
    //     $request = Yii::$app->request;
    //     $order = Order::findOne($id);
    //     $items = $order->items;
    //     $item = reset($items);
    //     return $this->render('view', [
    //         'order' => $order,
    //         'item' => $item,
    //         'back' => $request->get('ref', Url::to(['report/index']))
    //     ]);
    // }

    // public function actionGame()
    // {
    //     $this->view->params['main_menu_active'] = 'report.game';
    //     $request = Yii::$app->request;
    //     $data = [
    //         'start_date' => $request->get('start_date', date('Y-m-01')),
    //         'end_date' => $request->get('end_date', date('Y-m-t')),
    //     ];
    //     $form = new ReportByGameForm($data);
    //     $command = $form->getCommand();
    //     $pages = new Pagination(['totalCount' => $command->count()]);
    //     $models = $command->offset($pages->offset)
    //                         ->limit($pages->limit)
    //                         ->orderBy(['total' => SORT_DESC])
    //                         ->all();
    //     return $this->render('game', [
    //         'models' => $models,
    //         'pages' => $pages,
    //         'search' => $form,
    //     ]);
    // }

    // public function actionUser()
    // {
    //     $this->view->params['main_menu_active'] = 'report.user';
    //     $request = Yii::$app->request;
    //     $data = [
    //         'type' => $request->get('type', 'handler'),
    //         'start_date' => $request->get('start_date', date('Y-m-01')),
    //         'end_date' => $request->get('end_date', date('Y-m-t')),
    //     ];
    //     $form = new ReportByUserForm($data);
    //     $command = $form->getCommand();
    //     $pages = new Pagination(['totalCount' => $command->count()]);
    //     $models = $command->offset($pages->offset)
    //                         ->limit($pages->limit)
    //                         ->orderBy(['total_price' => SORT_DESC])
    //                         ->all();
    //     return $this->render('user', [
    //         'models' => $models,
    //         'pages' => $pages,
    //         'search' => $form,
    //     ]);
    // }
    // public function actionTransaction()
    // {
    //     $this->view->params['main_menu_active'] = 'report.transaction';
    //     $request = Yii::$app->request;
    //     $data = [
    //         'start_date' => $request->get('start_date', date('Y-m-01')),
    //         'end_date' => $request->get('end_date', date('Y-m-t')),
    //     ];
    //     $form = new ReportByTransactionForm($data);
    //     $command = $form->getCommand();
    //     $pages = new Pagination(['totalCount' => $command->count()]);
    //     $models = $command->offset($pages->offset)
    //                         ->limit($pages->limit)
    //                         ->orderBy(['id' => SORT_DESC])
    //                         ->all();
    //     return $this->render('transaction', [
    //         'models' => $models,
    //         'pages' => $pages,
    //         'search' => $form,
    //     ]);
    // }

    // New designs
    public function actionTransaction()
    {
        $this->view->params['main_menu_active'] = 'report.transaction';
        $request = Yii::$app->request;
        $data = [
            'start_date' => $request->get('start_date', date('Y-m-01')),
            'end_date' => $request->get('end_date', date('Y-m-t')),
            'discount_code' => $request->get('discount_code'),
            'user_id' => $request->get('user_id'),
            'auth_key' => $request->get('auth_key')
        ];
        $form = new ReportByTransactionForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('transaction', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionBalance()
    {
        $this->view->params['main_menu_active'] = 'report.balance';
        $request = Yii::$app->request;

        $start_date = $request->get('start_date', date('Y-m-01'));
        $end_date = $request->get('end_date', date('Y-m-t'));
        $user_id = $request->get('user_id');

        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'user_id' => $user_id,
        ];
        $form = new ReportByBalanceForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();


        // $userIds = ArrayHelper::getColumn($models, 'user_id');
        // $balances = [];
        // foreach ($userIds as $userId) {
        //     $countStartBalance = new GetUserWalletBalance([
        //         'date' => $form->start_date,
        //         'user_id' => $user_id,
        //     ]);
        //     $countEndBalance = new GetUserWalletBalance([
        //         'date' => $form->end_date,
        //         'user_id' => $user_id,
        //     ]);
        //     $balances[$userId] = [
        //         'start' => $countStartBalance->count(),
        //         'end' => $countEndBalance->count(),
        //     ];
        // }
        // print_r($balances);die;




        $inputCommand = $form->getInputCommand();
        $inputBalance = ArrayHelper::map($inputCommand->all(), 'id', 'coin');
        $outputCommand = $form->getOutputCommand();
        $outputBalance = ArrayHelper::map($outputCommand->all(), 'id', 'coin');

        // first/last balance





        return $this->render('balance', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'inputBalance' => $inputBalance,
            'outputBalance' => $outputBalance,
        ]);   
    }

    public function actionOrder()
    {
        $this->view->params['main_menu_active'] = 'report.order';
        $request = Yii::$app->request;
        $data = [
            'q' => $request->get('q'),
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

        return $this->render('order', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionGame()
    {
        $this->view->params['main_menu_active'] = 'report.game';
        $request = Yii::$app->request;
        $data = [
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new FetchOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_DESC])
                            ->all();

        return $this->render('game', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }
}
