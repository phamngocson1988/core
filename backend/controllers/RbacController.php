<?php
namespace backend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Url;

//forms
use backend\forms\FetchStaffForm;
use backend\forms\AssignRoleForm;

class RbacController extends Controller
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
        $this->view->params['main_menu_active'] = 'rbac.index';
        $request = Yii::$app->request;
        $q = $request->get('q');
        $status = $request->get('status', '');
        $form = new FetchStaffForm([
            'q' => $request->get('q'),
            'role' => $request->get('role'),
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

    public function actionRole()
    {
        $this->view->params['main_menu_active'] = 'rbac.role';
        $roles = Yii::$app->authManager->getRoles();
        return $this->render('role', [
            'models' => $roles,
        ]);
        
    }

    public function actionAssign()
    {
        $this->view->params['main_menu_active'] = 'rbac.role';
        $request = Yii::$app->request;
        $model = new AssignRoleForm();
        if (Yii::$app->request->isPost) {
            if ($model->load($request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
                return $this->redirect(['rbac/role']);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }    
        } else {
            $role = $request->get('role');
            $model->role = $role;
        }

        return $this->render('assign', [
            'model' => $model,
        ]);        
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'rbac.role';
        $model = new \backend\forms\CreateRoleForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $ref = Yii::$app->request->get('ref', Url::to(['rbac/role']));
                return $this->redirect($ref);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }    
        }
        return $this->render('create', [
            'model' => $model,
        ]);
        
    }
}