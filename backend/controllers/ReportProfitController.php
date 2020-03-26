<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;


class ReportProfitController extends Controller
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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionOrder()
    {
        $this->view->params['main_menu_active'] = 'report.cost.order';
        $request = Yii::$app->request;
        $data = [
            'id' => $request->get('id'),
            'confirmed_from' => $request->get('confirmed_from'),
            'confirmed_to' => $request->get('confirmed_to'),
            'payment_method' => $request->get('payment_method'),
        ];
        $form = new \backend\forms\ReportOrderProfitForm($data);
        $command = $form->getCommand();
        $command->with('user');
        $command->with('order');
        $command->with('game');
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['updated_at' => SORT_DESC])
                            ->all();

        return $this->render('order.php', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionGame()
    {
        $this->view->params['main_menu_active'] = 'report.cost.game';
        $request = Yii::$app->request;
        $data = [
            'game_ids' => $request->get('game_ids'),
            'confirmed_from' => $request->get('confirmed_from'),
            'confirmed_to' => $request->get('confirmed_to'),
            'limit' => $request->get('limit', '5'),
            'period' => $request->get('period', 'day'),
        ];
        $form = new \backend\forms\ReportGameProfitForm($data);
        $models = $form->getStatistic();
        return $this->render('game', [
            'models' => $models,
            'search' => $form,
        ]);
    }

    public function actionUser()
    {
        $this->view->params['main_menu_active'] = 'report.cost.user';
        $request = Yii::$app->request;
        $data = [
            'saler_id' => $request->get('saler_id'),
            'confirmed_from' => $request->get('confirmed_from'),
            'confirmed_to' => $request->get('confirmed_to'),
        ];
        $form = new \backend\forms\ReportSalerProfitForm($data);
        if (!$form->saler_id) {
            $games = [];
            $sumQuantityGame = 0;
            $totalQuantityGame = 0;

            $customers = [];
            $sumQuantityCustomer = 0;
            $totalQuantityCustomer = 0;
            $sumPriceCustomer = 0;
        } else {
            $command = $form->getCommand();
            $gameCommand = clone $command;
            $gameCommand = $gameCommand->groupBy(['game_id']);
            $gameCommand->select(['game_id', 'game_title', 'sum(quantity) as quantity']);
            $games = $gameCommand->orderBy(['quantity' => SORT_DESC])->asArray()->all();
            $sumQuantityGame = $gameCommand->sum('quantity');
            $totalQuantityGame = $gameCommand->count();

            $customerCommand = clone $command;
            $customerCommand = $customerCommand->groupBy(['customer_id']);
            $customerCommand->select(['customer_id', 'customer_name', 'sum(quantity) as quantity', 'sum(total_price * rate_usd) as total_price']);
            $customers = $customerCommand->orderBy(['quantity' => SORT_DESC])->asArray()->all();
            $sumQuantityCustomer = $customerCommand->sum('quantity');
            $sumPriceCustomer = $customerCommand->sum('total_price');
            $totalQuantityCustomer = $customerCommand->count();
        }
        return $this->render('user', [
            'games' => $games,
            'sumQuantityGame' => $sumQuantityGame,
            'totalQuantityGame' => $totalQuantityGame,

            'customers' => $customers,
            'sumQuantityCustomer' => $sumQuantityCustomer,
            'totalQuantityCustomer' => $totalQuantityCustomer,
            'sumPriceCustomer' => $sumPriceCustomer,

            'search' => $form,
        ]);
    }
}
