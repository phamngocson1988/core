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
use backend\forms\CreateOperatorMetaForm;
// models
use backend\models\User;
use backend\models\OperatorStaff;

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
            'status' => $request->get('status', ''),
            'language' => $request->get('language', ''),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        $managers = [];
        foreach ($models as $model) {
            $staffs = $model->fetchStaff(OperatorStaff::ROLE_ADMIN);
            $managers[$model->id] = reset($staffs);
        }
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
            'managers' => $managers
        ]);
    }

    public function actionCreate($language)
    {
        $this->view->params['main_menu_active'] = 'operator.index';        
        $request = Yii::$app->request;
        $model = new CreateOperatorForm(['language' => $language]);
        if ($model->load($request->post())) {
            if ($model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['operator/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
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
                Yii::$app->session->setFlash('error', $model->getErrors());
            }
        } else {
            $model->loadData();
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionMeta()
    {
        $this->view->params['main_menu_active'] = 'operator.meta';        
        $request = Yii::$app->request;
        $model = new CreateOperatorMetaForm();
        if ($request->isPost) {
            if ($model->load($request->post())) {
                if ($model->create()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                } else {
                    Yii::$app->session->setFlash('error', $model->getErrors());
                }
            }
        } else {
            $model->loadData();
        }

        return $this->render('meta', [
            'model' => $model,
        ]);
    }
}
