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
use backend\models\Order;
use common\models\User;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\forms\ReportProcessOrderByGame;
use backend\forms\ReportProcessOrderByUser;
use backend\forms\ReportSaleOrderByGame;
use backend\forms\FetchMyOrderForm;



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
    public function actionFinanceTransaction()
    {
        $this->view->params['main_menu_active'] = 'report.finance.transaction';
        $request = Yii::$app->request;
        $mode = $request->get('mode');
        $data = [
            'start_date' => $request->get('start_date', date('Y-m-01')),
            'end_date' => $request->get('end_date', date('Y-m-t')),
            'discount_code' => $request->get('discount_code'),
            'user_id' => $request->get('user_id'),
            'auth_key' => $request->get('auth_key')
        ];
        $form = new ReportByTransactionForm($data);
        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'giao-dich-nap-tien.xls';
            return $form->export($fileName);
        }
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('finance/transaction', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionFinanceBalance()
    {
        $this->view->params['main_menu_active'] = 'report.finance.balance';
        $request = Yii::$app->request;

        $start_date = $request->get('start_date', date('Y-m-01'));
        $end_date = $request->get('end_date', date('Y-m-t'));
        $user_id = $request->get('user_id');
        $mode = $request->get('mode');
        
        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'user_id' => $user_id,
        ];
        $form = new ReportByBalanceForm($data);
        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'so-du-tai-khoan.xls';
            return $form->export($fileName);
        }
        $command = $form->getUserCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        $report = $form->fetch();
        return $this->render('finance/balance', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'report' => $report,
        ]);   
    }

    public function actionFinanceBalanceDetail($id)
    {
        $this->view->params['main_menu_active'] = 'report.finance.balance';
        $request = Yii::$app->request;

        $start_date = $request->get('start_date', date('Y-m-01'));
        $end_date = $request->get('end_date', date('Y-m-t'));
        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'user_id' => $id,
        ];
        $form = new ReportByBalanceForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        $user = User::findOne($id);
        return $this->render('finance/balance-detail', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'user' => $user
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
        $form = new ReportProcessOrderByGame($data);
        $models = $form->fetch();

        return $this->render('game', [
            'models' => $models,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionUser()
    {
        $this->view->params['main_menu_active'] = 'report.user';
        $request = Yii::$app->request;
        $data = [
            'handler_id' => $request->get('handler_id'),
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new ReportProcessOrderByUser($data);
        $models = $form->fetch();

        return $this->render('user', [
            'models' => $models,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionSaleOrder()
    {
        $this->view->params['main_menu_active'] = 'report.sale.order';
        $request = Yii::$app->request;
        $data = [
            'q' => $request->get('q'),
            'saler_id' => $request->get('saler_id'),
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

        return $this->render('sale/order', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionSaleGame()
    {
        $this->view->params['main_menu_active'] = 'report.sale.game';
        $request = Yii::$app->request;
        $data = [
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new ReportSaleOrderByGame($data);
        $command = $form->getCommand();
        $models = $command->asArray()->all();
        return $this->render('sale/game', [
            'models' => $models,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionSaleUser()
    {
        $this->view->params['main_menu_active'] = 'report.sale.user';
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
        return $this->render('sale/user', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }
}
