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
        $isAdmin = Yii::$app->user->can('admin');
        $form = new \backend\forms\FetchCustomerTrackerForm([
            'name' => $request->get('name'),
            'saler_id' => $isAdmin ? $request->get('saler_id') : Yii::$app->user->id,
            'country_code' => $request->get('country_code'),
            'phone' => $request->get('phone'),
            'game' => $request->get('game'),
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
        $model = \backend\models\CustomerTracker::findOne($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'customer-tracker.index';
        $model = new \backend\forms\EditCustomerTrackerForm(['id' => $id]);
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['customer-tracker/index']);
        } else {
            $model->loadData();
        }
        $comments = LeadTrackerComment::find()->where(['lead_tracker_id' => $id])->all();
        return $this->render('edit', ['model' => $model, 'comments' => $comments]);
    }

    public function actionAddAction($id, $action)
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            return $this->renderPartial('_comment', ['id' => $id]);
        } else {
            $leadTracker = CustomerTracker::findOne($id);
            $content = $request->post('content');
            if (trim($content)) {
                $leadTracker->addAction($action, $content);
            }
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
}
