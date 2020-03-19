<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;

use backend\models\User;

use backend\forms\CreateRoleForm;
use backend\forms\EditRoleForm;
use backend\forms\AssignRoleForm;
use backend\forms\RevokeRoleForm;

class RbacController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['role', 'assign-role', 'revoke-role', 'user-role'],
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionRole()
    {
        $this->view->params['main_menu_active'] = 'user.role';
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $countRoles = [];
        foreach ($roles as $role) {
            $countRoles[$role->name] = count($auth->getUserIdsByRole($role->name));
        }
        return $this->render('role.tpl', [
            'models' => $roles,
            'countRoles' => $countRoles,
            'ref' => Url::to(Yii::$app->request->getUrl(), true),
        ]);
    }

    public function actionCreateRole()
    {
        $this->view->params['main_menu_active'] = 'user.role';
        $model = new CreateRoleForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(Url::to(['rbac/role']));
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }    
        }
        return $this->render('create-role.tpl', [
            'model' => $model,
        ]);
    }

    public function actionEditRole($name)
    {
        $this->view->params['main_menu_active'] = 'user.role';
        $model = new EditRoleForm();
        $model->name = $name;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $ref = Yii::$app->request->get('ref', Url::to(['rbac/role']));
                return $this->redirect($ref);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }    
        } else {
            $model->loadData();
        }
        return $this->render('edit-role.tpl', [
            'model' => $model,
        ]);
        
    }

    public function actionUserRole()
    {
        $this->view->params['main_menu_active'] = 'user.role';
        $name = Yii::$app->request->get('name');
        if (!$name) throw new NotFoundHttpException('Not found');
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);
        $userIds = $auth->getUserIdsByRole($role->name);
        $models = User::findAll($userIds);
        return $this->render('user-role.tpl', [
            'role' => $role,
            'models' => $models,
        ]);
        
    }

    public function actionRevokeRole()
    {
        $this->view->params['main_menu_active'] = 'user.role';
        $request = Yii::$app->request;
        $user_id = $request->get('user_id');
        $roleName = $request->get('role');
        if( $request->isPost) {
            $form = new RevokeRoleForm(['user_id' => $user_id, 'role' => $roleName]);
            if ($form->validate() && $form->revoke()) {
                return $this->asJson(['status' => true]);
            } else {
                $errors = $form->getErrorSummary(false);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'error' => $error]);
            }
        }
        $user = User::findOne($user_id);
        $role = Yii::$app->authManager->getRole($roleName);
        return $this->renderPartial('_revoke-role-modal.php', ['user' => $user, 'role' => $role]);
    }

    public function actionAssignRole()
    {
        $this->view->params['main_menu_active'] = 'user.role';
        $request = Yii::$app->request;
        $model = new AssignRoleForm();
        if (Yii::$app->request->isPost) {
            if ($model->load($request->post()) && $model->assign()) {
                Yii::$app->session->setFlash('success', 'Thiết lập vai trò cho nhân viên thành công');
                return $this->redirect(Url::to(['rbac/user-role', 'name' => $model->role]));
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }    
        } else {
            $role = $request->get('role');
            $user_id = $request->get('user_id');
            $model->role = $role;
            $model->user_id = $user_id;
        }

        $links = [
            'user_suggestion' => Url::to(['user/suggestion'])
        ];
        return $this->render('assign-role', [
            'model' => $model,
            'links' => $links
        ]);        
    }
}