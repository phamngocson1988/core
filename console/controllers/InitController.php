<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class InitController extends Controller
{
    // php yii init/init
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // User: admin
        $form = new \backend\forms\SignupForm([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'phamngocson1988@gmail.com',
            'password' => '123456'
        ]);
        $user = $form->create();

        foreach (Yii::$app->user->fixRoles as $roleName) {
            $role = $auth->createRole($roleName);
            $role->description = ucfirst($roleName);
            $auth->add($role);

            $auth->assign($role, $user->id);
        }
        die('Done');        

        
    }
}