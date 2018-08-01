<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\forms\FetchDepartmentForm;
use backend\forms\CreateDepartmentForm;
use backend\forms\EditDepartmentForm;
use backend\forms\ChangeDepartmentStatusForm;

/**
 * DepartmentController
 */
class DepartmentController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'edit'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'department.index';
        $request = Yii::$app->request;
        $form = new FetchDepartmentForm();
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index.tpl', [
            'models' => $models,
            'pages' => $pages,
            'form' => $form,
            'ref' => Url::to($request->getUrl(), true),
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'department.index';
        $request = Yii::$app->request;
        $model = new EditDepartmentForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['department/index']));
                return $this->redirect($ref);
            } else {
                Yii::$app->session->setFlash('error', $model->getFirstErrors());
            }
        } else {
            $model->loadData($id);
        }
        return $this->render('edit.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['department/index']))
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'department.index';
        $request = Yii::$app->request;
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $model = new CreateDepartmentForm();
        if ($model->load($request->post())) {
            if ($user = $model->create()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['department/index']);
            } else {
                Yii::$app->session->setFlash('error', $model->getFirstErrors());
            }
        }
        return $this->render('create.tpl', [
            'model' => $model,
            'back' => $request->get('ref', Url::to(['department/index']))
        ]);
    }
}
