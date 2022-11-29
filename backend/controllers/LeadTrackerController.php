<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;

class LeadTrackerController extends Controller
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

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'lead-tracker.index';
        $models = \backend\models\LeadTracker::find()->all();
        return $this->render('index', ['models' => $models]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'lead-tracker.index';
        $model = new \backend\forms\CreateLeadTrackerForm();
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['lead-tracker/index']);
        }
        return $this->render('edit', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'lead-tracker.index';
        $model = new \backend\forms\EditLeadTrackerForm(['id' => $id]);
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['lead-tracker/index']);
        } else {
            $model->loadData();
        }
        return $this->render('edit', ['model' => $model]);
    }

    public function actionConvert($id)
    {
        $model = new \backend\forms\ConvertLeadTrackerToCustomerForm(['id' => $id]);
        if ($model->convert()) {
            Yii::$app->session->setFlash('error', 'Error');
        }
        return $this->redirect(['lead-tracker/index']);
    }
}
