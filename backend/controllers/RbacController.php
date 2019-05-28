<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use common\models\User;
use yii\base\InvalidParamException;
use backend\forms\CreateRoleForm;
use backend\forms\EditRoleForm;
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

    public function actionAssignSaler($id)
    {
        $auth = Yii::$app->authManager;
        $sale = $auth->getRole('saler');
        $auth->assign($sale, $id);
    }

    public function actionAssignHandler($id)
    {
        $auth = Yii::$app->authManager;
        $sale = $auth->getRole('handler');
        $auth->assign($sale, $id);
    }

    public function actionAssignCustomer()
    {
        $auth = Yii::$app->authManager;
        $customer = $auth->getRole('customer');
        $users = User::find()->all();
        foreach ($users as $user) {
            $roles = $auth->getRolesByUser($user->id);
            if (!$roles) {
                $auth->assign($customer, $user->id);
            }
        }
    }

    public function actionCreateEditOrderPermission()
    {
        $auth = Yii::$app->authManager;
        $rule = new \backend\rbac\EditOrderRule;
        $auth->add($rule);

        $permission = $auth->createPermission('edit_order');
        $permission->description = 'Edit order';
        $permission->ruleName = $rule->name;
        $auth->add($permission);

        $saler = $auth->getRole('saler');
        $auth->addChild($saler, $permission);

        $handler = $auth->getRole('handler');
        $auth->addChild($handler, $permission);
    }

    public function actionCreateTakenOrderPermission()
    {
        $auth = Yii::$app->authManager;
        $rule = new \backend\rbac\TakenOrderRule;
        $auth->add($rule);

        $permission = $auth->createPermission('taken_order');
        $permission->description = 'Taken order';
        $permission->ruleName = $rule->name;
        $auth->add($permission);

        $saler = $auth->getRole('saler');
        $auth->addChild($saler, $permission);

        $handler = $auth->getRole('handler');
        $auth->addChild($handler, $permission);
    }

    public function actionCreateDeleteOrderPermission()
    {
        $auth = Yii::$app->authManager;
        $rule = new \backend\rbac\DeleteOrderRule;
        $auth->add($rule);

        $permission = $auth->createPermission('delete_order');
        $permission->description = 'Delete order';
        $permission->ruleName = $rule->name;
        $auth->add($permission);

        $saler = $auth->getRole('saler');
        $auth->addChild($saler, $permission);

        $handler = $auth->getRole('handler');
        $auth->addChild($handler, $permission);
    }

    public function actionCreateCancelOrderPermission()
    {
        $auth = Yii::$app->authManager;
        $rule = new \backend\rbac\CancelOrderRule;
        $auth->add($rule);

        $permission = $auth->createPermission('cancel_order');
        $permission->description = 'Cancel order';
        $permission->ruleName = $rule->name;
        $auth->add($permission);

        $saler = $auth->getRole('saler');
        $auth->addChild($saler, $permission);

        $handler = $auth->getRole('handler');
        $auth->addChild($handler, $permission);
    }

    public function actionCreateViewCustomerPermission()
    {
        $auth = Yii::$app->authManager;
        $rule = new \backend\rbac\ViewCustomerRule;
        $auth->add($rule);

        $permission = $auth->createPermission('view_customer');
        $permission->description = 'View customer';
        $permission->ruleName = $rule->name;
        $auth->add($permission);

        $saler = $auth->getRole('saler');
        $auth->addChild($saler, $permission);

        $handler = $auth->getRole('handler');
        $auth->addChild($handler, $permission);
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
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }    
        } else {
            $role = $request->get('role');
            $model->role = $role;
        }

        $links = [
            'user_suggestion' => Url::to(['user/suggestion'])
        ];
        return $this->render('assign-role', [
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
                $ref = Yii::$app->request->get('ref', Url::to(['rbac/role']));
                return $this->redirect($ref);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }    
        }
        return $this->render('create-role.tpl', [
            'model' => $model,
        ]);
        
    }

    public function actionEditRole($name)
    {
        $this->view->params['main_menu_active'] = 'rbac.role';
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

    public function actionChangeRole($userId)
    {
        $model = new AssignRoleForm(['user_id' => $userId]);
        $model->scenario = AssignRoleForm::SCENARIO_EDIT;
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->goBack();
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }    
        }

        return $this->render('change-role.tpl', [
            'model' => $model,
        ]);
        
    }

    public function actionActionList()
    {
        // list all modules
        $controllerlist = [];
        $modules = Yii::$app->modules;
        $controllerDirs[Yii::$app->id] = Yii::$app->controllerPath;
        foreach ($modules as $key => $module) { 
            if (is_object($module)) {
                $controllerDirs[$key] = $module->controllerPath;
            } elseif (is_array($module) && array_key_exists('class', $module)) {
                $moduleObj = Yii::createObject($module, [$key]);
                $controllerDirs[$key] = $moduleObj->controllerPath;
            }
        }

        // list all controllers
        foreach ($controllerDirs as $key => $dir) {
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && substr($file, strrpos($file, '.') - 10) == 'Controller.php') {
                        $controllerId = substr($file, 0, strrpos($file, '.') - 10);
                        $controllerId = strtolower($controllerId);
                        $controllerlist[$key][$controllerId] = $dir . DIRECTORY_SEPARATOR . $file;
                    }
                }
                closedir($handle);
            }    
        }
        asort($controllerlist);

        // list all actions
        $fulllist = [];
        foreach ($controllerlist as $moduleName => $controllers) {
            foreach ($controllers as $name => $controller) {
                $handle = fopen($controller, "r");
                if ($handle) {
                    while (($line = fgets($handle)) !== false) {
                        if (preg_match('/public function action(.*?)\(/', $line, $display)) {
                            if (strlen($display[1]) > 2) {
                                $fulllist[$moduleName][$name][] = strtolower($display[1]);
                            }
                        }
                    }
                }
                fclose($handle);
            }
        }
        echo '<pre>';
        print_r($fulllist);die;
        return $fulllist;
    }
}