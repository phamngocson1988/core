<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

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
        return $this->render('index', [
            // 'models' => $models,
            // 'pages' => $pages,
            // 'search' => $form,
        ]);
    }
}
