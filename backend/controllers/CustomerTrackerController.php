<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use backend\models\CustomerTracker;
use backend\models\LeadTrackerComment;

class CustomerTrackerController extends Controller
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
        $isAdmin = Yii::$app->user->cans(['admin', 'sale_manager']);
        $form = new \backend\forms\FetchCustomerTrackerForm([
            'name' => $request->get('name'),
            'saler_id' => $isAdmin ? $request->get('saler_id') : Yii::$app->user->id,
            'country_code' => $request->get('country_code'),
            'phone' => $request->get('phone'),
            'game_id' => $request->get('game_id'),
            'email' => $request->get('email'),
            'sale_growth' => $request->get('sale_growth'),
            'product_growth' => $request->get('product_growth'),
            'is_loyalty' => $request->get('is_loyalty'),
            'is_dangerous' => $request->get('is_dangerous'),
        ]);
        $mode = $request->get('mode');
        if ($mode === 'export') {
            $fileName = date('YmdHis') . '-customer-tracker.xls';
            return $form->export($fileName);
        }
        $models = $form->getCommand()->all();

        return $this->render('index', [
            'models' => $models,
            'search' => $form,
        ]);

    }

    public function actionView($id)
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'customer-tracker.index';
        $form = new \backend\forms\CustomerTrackerDetailForm(['id' => $id]);
        $model = $form->getCustomerTracker();
        return $this->render('view', ['model' => $model, 'form' => $form]);
    }

    public function actionReport()
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'customer-tracker.index';
        $form = new \backend\forms\CustomerTrackerReportForm();
        return $this->render('report', ['form' => $form]);
    }

    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'customer-tracker.index';
        $model = new \backend\forms\EditCustomerTrackerForm(['id' => $id]);
        if ($model->load($request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Success');
        } else {
            $model->loadData();
        }
        $comments = LeadTrackerComment::find()->where(['lead_tracker_id' => $id])->all();
        return $this->render('edit', ['model' => $model, 'comments' => $comments]);
    }

    public function actionAddAction($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\CreateCustomerContactLogForm(['lead_tracker_id' => $id]);
        if ($request->isGet) {
            return $this->renderPartial('_comment', ['model' => $model]);
        } else {
            $model->load($request->post());
            $model->save();
            return $this->renderJson(true);
        }
    }

    public function actionConvert($id)
    {
        $model = new \backend\forms\ConvertCustomerToCustomerTrackerForm(['id' => $id]);
        if (!$model->convert()) {
            $errors = $model->getFirstErrors();
            Yii::$app->session->setFlash('error', reset($errors));
        } else {
            Yii::$app->session->setFlash('success', 'Success');
        }
        return $this->redirect(['customer-tracker/index']);
    }

    public function actionCalculate($id)
    {
        $model = new \common\forms\CalculateCustomerTrackerPerformanceForm(['id' => $id]);
        return $this->asJson(['status' => $model->run()]);
    }

    public function actionDelete($id)
    {
        $convertCustomerService = new \backend\forms\ConvertLeadTrackerToCustomerForm(['id' => $id]);
        $user = \backend\models\CustomerTracker::findOne($id);
        if ($user) {
            $user->delete();
        }
        Yii::$app->session->setFlash('success', 'Success');
        return $this->asJson(['status' => true]);
    }

    public function actionUpdateSurveyAnswer() 
    {
        $request = Yii::$app->request;
        $content = $request->post();
        return $this->asJson([$id, $content]);
    }
}
