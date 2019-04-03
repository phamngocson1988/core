<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\forms\FetchOrderForm;
use backend\forms\ReportByGameForm;
use backend\forms\ReportByUserForm;
use yii\data\Pagination;


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
    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'report.index';
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
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionGame()
    {
        $this->view->params['main_menu_active'] = 'report.game';
        $request = Yii::$app->request;
        $data = [
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new ReportByGameForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['total' => SORT_DESC])
                            ->all();
        return $this->render('game', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionUser()
    {
        $this->view->params['main_menu_active'] = 'report.user';
        $request = Yii::$app->request;
        $data = [
            'type' => $request->get('type', 'handler'),
            'start_date' => $request->get('start_date', date('Y-m-d', strtotime('-29 days'))),
            'end_date' => $request->get('end_date', date('Y-m-d')),
        ];
        $form = new ReportByUserForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['total_price' => SORT_DESC])
                            ->all();
        return $this->render('user', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }
}
