<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use common\models\User;
use yii\base\InvalidParamException;
use backend\forms\CreateRoleForm;
use backend\forms\AssignRoleForm;
use backend\forms\RevokeRoleForm;
use backend\forms\FetchUserByRoleForm;
use yii\helpers\Url;
use yii\data\Pagination;
use yii\filters\AccessControl;

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
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionInitRole()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Role: admin
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
    }

    public function actionAssignAdmin($id)
    {
        $auth = Yii::$app->authManager;
        $admin = $auth->getRole('admin');
        $auth->assign($admin, $id);
    }

    public function actionAssignSale($id)
    {
        $auth = Yii::$app->authManager;
        $sale = $auth->getRole('sale');
        $auth->assign($sale, $id);
    }

    //=============== ROLE ===============
    public function actionRole()
    {
        $this->view->params['main_menu_active'] = 'rbac.role';
        $roles = Yii::$app->authManager->getRoles();
        return $this->render('role.tpl', [
            'models' => $roles,
            'ref' => Url::to(Yii::$app->request->getUrl(), true),
        ]);
        
    }

    public function actionUserRole()
    {
        $this->view->params['main_menu_active'] = 'rbac.role';
        $name = Yii::$app->request->get('name');
        $role = Yii::$app->authManager->getRole($name);

        $form = new FetchUserByRoleForm(['role' => $name]);
        $models = $form->fetch();

        return $this->render('user-role.tpl', [
            'role' => $role,
            'models' => $models,
            'ref' => Url::to(Yii::$app->request->getUrl(), true),
        ]);
        
    }

    public function actionRevokeRole()
    {
        $this->view->params['main_menu_active'] = 'rbac.role';
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user_id = $request->get('user_id');
            $role = $request->get('role');
            $form = new RevokeRoleForm(['user_id' => $user_id, 'role' => $role, 'scenario' => RevokeRoleForm::SCENARIO_REVOKE]);
            $result = $form->revoke();
            return $this->renderJson($result, null, $form->getErrors());
        }
    }

    public function actionAssignRole()
    {
        $this->view->params['main_menu_active'] = 'rbac.role';
        $request = Yii::$app->request;
        $model = new AssignRoleForm();
        $model->scenario = AssignRoleForm::SCENARIO_ADD;
        if (Yii::$app->request->isPost) {
            if ($model->load($request->post()) && $model->save()) {
                $ref = $request->get('ref', Url::to(['user/index']));
                return $this->redirect($ref);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }    
        } else {
            $role = $request->get('role');
            $model->role = $role;
        }

        $links = [
            'user_suggestion' => Url::to(['user/suggestion'])
        ];
        return $this->render('assign-role.tpl', [
            'model' => $model,
            'links' => $links
        ]);        
    }

    public function actionCreateRole()
    {
        $this->view->params['main_menu_active'] = 'rbac.role';
        $model = new CreateRoleForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->goBack();
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }    
        }
        return $this->render('create-role.tpl', [
            'model' => $model,
        ]);
        
    }

    public function actionChangeRole($userId)
    {
        $model = new AssignRoleForm(['user_id' => $userId]);
        $model->scenario = AssignRoleForm::SCENARIO_EDIT;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->goBack();
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }    
        }

        return $this->render('change-role.tpl', [
            'model' => $model,
        ]);
        
    }
}