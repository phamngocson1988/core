<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use backend\models\LeadTracker;

class LeadTrackerSurveyController extends Controller
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
        $this->view->params['main_menu_active'] = 'customer-tracker.index';
        $request = Yii::$app->request;
        $models = \common\models\LeadTrackerSurvey::find()->all();

        return $this->render('index', [
            'models' => $models,
        ]);

    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'customer-tracker.index';
        $model = new \backend\forms\CreateLeadTrackerSurveyForm();
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Create survey successfully');
            return $this->redirect(['lead-tracker-survey/index']);
        }
        return $this->render('create', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'customer-tracker.index';
        $model = new \backend\forms\EditLeadTrackerSurveyForm(['id' => $id]);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Edit survey question successfully');
            return $this->redirect(['lead-tracker-survey/index']);
        } else {
            $model->loadData();
        }
        return $this->render('edit', ['model' => $model]);
    }

    public function actionCreateQuestion()
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'customer-tracker.index';
        $model = new \backend\forms\CreateLeadTrackerSurveyQuestionForm();
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Create survey question successfully');
            return $this->redirect(['lead-tracker-survey/index']);
        }
        $comments = [];
        return $this->render('create-question', ['model' => $model, 'comments' => $comments]);
    }

    public function actionEditQuestion($id)
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'customer-tracker.index';
        $model = new \backend\forms\EditLeadTrackerSurveyQuestionForm(['id' => $id]);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Edit survey question successfully');
            return $this->redirect(['lead-tracker-survey/index']);
        } else {
            $model->loadData();
        }
        return $this->render('edit-question', ['model' => $model]);
    }


    public function actionDelete($id)
    {
        $convertCustomerService = new \backend\forms\ConvertLeadTrackerToCustomerForm(['id' => $id]);
        $user = \backend\models\LeadTracker::findOne($id);
        if ($user) {
            $user->delete();
        }
        Yii::$app->session->setFlash('success', 'Success');
        return $this->asJson(['status' => true]);
    }
}
