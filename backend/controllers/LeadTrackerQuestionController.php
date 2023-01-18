<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use backend\models\LeadTracker;
use backend\models\LeadTrackerComment;

class LeadTrackerQuestionController extends Controller
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
        $request = Yii::$app->request;
        $models = \common\models\LeadTrackerQuestion::find()->all();

        return $this->render('index', [
            'models' => $models,
        ]);

    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'lead-tracker.index';
        $model = new \backend\forms\CreateLeadTrackerQuestionForm();
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['lead-tracker-question/index']);
        }
        $comments = [];
        return $this->render('edit', ['model' => $model, 'comments' => $comments]);
    }

    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'lead-tracker.index';
        $model = new \backend\forms\EditLeadTrackerQuestionForm(['id' => $id]);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Edit user successfully');
        } else {
            $model->loadData();
        }
        return $this->render('edit', ['model' => $model]);
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
