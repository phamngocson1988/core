<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use backend\models\LeadTracker;
use backend\models\LeadTrackerComment;

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
        $request = Yii::$app->request;
        $isAdmin = Yii::$app->user->cans(['admin', 'sale_manager']);
        $form = new \backend\forms\FetchLeadTrackerForm([
            'id' => $request->get('id'),
            'saler_id' => $isAdmin ? $request->get('saler_id') : Yii::$app->user->id,
            'country_code' => $request->get('country_code'),
            'phone' => $request->get('phone'),
            'game_id' => $request->get('game_id'),
            'email' => $request->get('email'),
            'is_potential' => $request->get('is_potential'),
            'is_target' => $request->get('is_target'),
        ]);
        $mode = $request->get('mode');
        if ($mode === 'export') {
            $fileName = date('YmdHis') . '-lead-tracker.xls';
            return $form->export($fileName);
        }
        $models = $form->getCommand()->all();

        return $this->render('index', [
            'models' => $models,
            'search' => $form,
        ]);

    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'lead-tracker.index';
        $model = new \backend\forms\CreateLeadTrackerForm();
        $model->setScenario(\backend\forms\CreateLeadTrackerForm::SCENARIO_CREATE);
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['lead-tracker/index']);
        }
        $comments = [];
        return $this->render('edit', ['model' => $model, 'comments' => $comments]);
    }

    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'lead-tracker.index';
        $model = new \backend\forms\EditLeadTrackerForm(['id' => $id]);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Edit user successfully');
        } else {
            $model->loadData();
        }
        $comments = LeadTrackerComment::find()->where(['lead_tracker_id' => $id])->all();
        return $this->render('edit', ['model' => $model, 'comments' => $comments]);
    }

    public function actionConvert($id)
    {
        $convertCustomerService = new \backend\forms\ConvertLeadTrackerToCustomerForm(['id' => $id]);
        $user = $convertCustomerService->convert();
        if (!$user) {
            $errors = $convertCustomerService->getFirstErrors();
            Yii::$app->session->setFlash('error', reset($errors));
        } else {
            Yii::$app->session->setFlash('success', 'Create new user successfully');
            $convertTrackerService = new \backend\forms\ConvertCustomerToCustomerTrackerForm([
                'id' => $user->id,
                'lead_tracker_id' => $id
            ]);
            if (!$convertTrackerService->convert()) {
                $errors = $convertTrackerService->getFirstErrors();
                Yii::$app->session->setFlash('error', reset($errors));
            }
        }
        return $this->redirect(['lead-tracker/index']);
    }

    public function actionAddComment($id)
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            return $this->renderPartial('_comment', ['id' => $id]);
        } else {
            $leadTracker = LeadTracker::findOne($id);
            $content = $request->post('content');
            if (trim($content)) {
                $leadTracker->addComment($content);
            }
            return $this->renderJson(true);
        }
    }

    public function actionConvertUserToTracker($id)
    {
        $model = new \backend\forms\ConvertLeadTrackerToCustomerForm(['id' => $id]);
        if (!$model->convert()) {
            $errors = $model->getFirstErrors();
            Yii::$app->session->setFlash('error', reset($errors));
        } else {
            Yii::$app->session->setFlash('success', 'Success');
        }
        return $this->redirect(['customer-tracker/index']);
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
