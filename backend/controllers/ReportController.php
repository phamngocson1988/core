<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\forms\FetchOrderForm;
use backend\forms\ReportByGameForm;
use backend\forms\ReportByUserForm;
use backend\forms\ReportByTransactionForm;
use backend\forms\ReportByBalanceForm;
use yii\data\Pagination;
use backend\models\Order;
use common\models\User;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\forms\ReportProcessOrderByGame;
use backend\forms\ReportProcessOrderByUser;
use backend\forms\ReportSaleOrderByGame;
use backend\forms\FetchMyOrderForm;
use backend\forms\StatisticsByTransactionForm;
use backend\forms\StatisticsByOrderForm;
use backend\forms\ReportCostOrderBySaler;

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

    public function actionFinanceTransactionStatistics()
    {
        $this->view->params['main_menu_active'] = 'report.finance.transaction';
        $request = Yii::$app->request;
        $data = [
            'start_date' => $request->get('start_date', date('Y-m-01')),
            'end_date' => $request->get('end_date', date('Y-m-t')),
            'period' => $request->get('period', 'day'),
        ];
        $form = new StatisticsByTransactionForm($data);
        $command = $form->getCommand();
        $models = $command->asArray()->all();
        return $this->render('finance/transaction-statistics', [
            'models' => $models,
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
        $mode = $request->get('mode');
        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'user_id' => $id,
        ];
        $form = new ReportByBalanceForm($data);
        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'chi-tiet-giao-dich.xls';
            return $form->exportDetail($fileName);
        }
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

    public function actionProcessOrder()
    {
        $this->view->params['main_menu_active'] = 'report.process.order';
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

        return $this->render('process/order', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionProcessGame()
    {
        $this->view->params['main_menu_active'] = 'report.process.game';
        $request = Yii::$app->request;
        $data = [
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new ReportProcessOrderByGame($data);
        $models = $form->fetch();

        return $this->render('process/game', [
            'models' => $models,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionProcessUser()
    {
        $this->view->params['main_menu_active'] = 'report.process.user';
        $request = Yii::$app->request;
        $data = [
            'handler_id' => $request->get('handler_id'),
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new ReportProcessOrderByUser($data);
        $models = $form->fetch();

        return $this->render('process/user', [
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
            'status' => [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_COMPLETED]
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

    public function actionSaleOrderStatistics()
    {
        $this->view->params['main_menu_active'] = 'report.sale.order';
        $request = Yii::$app->request;
        $data = [
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new StatisticsByOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->asArray()
                            ->all();

        return $this->render('sale/order-statistics', [
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

    public function actionCostOrder()
    {
        $this->view->params['main_menu_active'] = 'report.cost.order';
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

        return $this->render('cost/order', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCostOrderStatistics()
    {
        $this->view->params['main_menu_active'] = 'report.cost.order';
        $request = Yii::$app->request;
        $data = [
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new StatisticsByOrderForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->asArray()
                            ->all();

        return $this->render('cost/order-statistics', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCostGame()
    {
        $this->view->params['main_menu_active'] = 'report.cost.game';
        $request = Yii::$app->request;
        $data = [
            'game_id' => $request->get('game_id'),
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new ReportSaleOrderByGame($data);
        $command = $form->getCommand();
        $models = $command->asArray()->all();

        return $this->render('cost/game', [
            'models' => $models,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionCostUser()
    {
        $this->view->params['main_menu_active'] = 'report.cost.user';
        $request = Yii::$app->request;
        $data = [
            'saler_id' => $request->get('saler_id'),
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new ReportCostOrderBySaler($data);
        $models = $form->getCommand()->asArray()->all();

        return $this->render('cost/user', [
            'models' => $models,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }
}
