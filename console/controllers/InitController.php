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
            'username' => 'admin',
            'email' => 'phamngocson1988@gmail.com',
            'password' => '123456',
            'firstname' => 'System',
            'lastname' => 'Admin',
            'country' => 'VN',
            'gender' => 'M',
        ]);
        $user = $form->create();

        // Create roles / permisions
        
        // Assign system_admin to user
        
    }
}