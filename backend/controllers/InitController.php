<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;

class InitController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    // php yii init/init
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // User: admin
        $form = new \backend\forms\CreateUserForm([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'phamngocson1988@gmail.com',
            'password' => '123456'
        ]);
        $user = $form->create();

        foreach (Yii::$app->user->fixRoles as $roleName) {
            $role = $auth->createRole($roleName);
            $role->description = $roleName;
            $auth->add($role);

            $assignForm = new \backend\forms\AssignRoleForm([
                'role' => $roleName,
                'user_id' => $user->id
            ]);
            $assignForm->assign();
        }
        die('Done');        

        
    }
}