<?php
namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

//forms
use backend\forms\FetchOperatorForm;
use backend\forms\CreateOperatorForm;
use backend\forms\EditOperatorForm;
// models
use backend\models\User;

class OperatorController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['system'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'operator.index';
        $request = Yii::$app->request;
        $form = new FetchOperatorForm([
            'q' => $request->get('q'),
            'status' => $request->get('status'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        $operatorIds = ArrayHelper::getColumn($models, 'id');
        $managers = User::find()->where(['in', 'operator_id', $operatorIds])
        ->select(['operator_id', 'firstname', 'lastname'])->all();
        $managers = ArrayHelper::map($managers, 'operator_id', function($user, $defaultValue) {
            return sprintf("%s %s", $user->firstname, $user->lastname);
        });
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'managers' => $managers
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'operator.index';        
        $request = Yii::$app->request;
        $model = new CreateOperatorForm();
        if ($model->load($request->post())) {
            if ($model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['operator/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'operator.index';        
        $request = Yii::$app->request;
        $model = new EditOperatorForm(['id' => $id]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['operator/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->loadData();
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }
}
