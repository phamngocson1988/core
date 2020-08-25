<?php
namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;

//forms
use backend\forms\FetchUserForm;
use backend\forms\SignupForm;
use backend\forms\EditUserForm;

/**
 * UserController
 */
class UserController extends Controller
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
        $this->view->params['main_menu_active'] = 'user.index';
        $request = Yii::$app->request;
        $form = new FetchUserForm([
            'q' => $request->get('q'),
            'status' => $request->get('status'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'search' => $form,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'user.index';        
        $request = Yii::$app->request;
        $model = new SignupForm();
        if ($model->load($request->post())) {
            if ($user = $model->signup()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['user/index']);
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
        $this->view->params['main_menu_active'] = 'user.index';        
        $request = Yii::$app->request;
        $model = new EditUserForm(['id' => $id]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['user/index']);
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
