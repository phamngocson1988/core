<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\Url;

use common\models\OrderComplainTemplate;

/**
 * OrderComplainController
 */
class OrderComplainController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['delete'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'ordercomplain.index';
        $command = OrderComplainTemplate::find()->orderBy(['id' => SORT_ASC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'template' => new OrderComplainTemplate()
        ]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new OrderComplainTemplate(['scenario' => OrderComplainTemplate::SCENARIO_CREATE]);
        if ($model->load($request->post()) && $model->save()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $model = OrderComplainTemplate::findOne($id);
        $model->scenario = OrderComplainTemplate::SCENARIO_EDIT; 
        if ($model->load($request->post()) && $model->save()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = OrderComplainTemplate::findOne($id);
        if ($model && $model->delete()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

}
