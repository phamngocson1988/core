<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class InitController extends Controller
{
    // php yii init/init
    public function actionInit()
    {
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
        $user = $form->signup();

        // Create roles / permisions
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Role: system
        $system = $auth->createRole('system');
        $system->description = 'System Administrator';
        $auth->add($system);

        // Role: admin
        $admin = $auth->createRole('admin');
        $admin->description = 'Operator Administrator';
        $auth->add($admin);

        // Role: manager
        $manager = $auth->createRole('manager');
        $manager->description = 'Operator Manager';
        $auth->add($manager);

        // Role: moderator
        $moderator = $auth->createRole('moderator');
        $moderator->description = 'Operator Moderator';
        $auth->add($moderator);

        // Permission: manage_operator
        $manageOperatorPermission = $auth->createPermission('manage_operator');
        $manageOperatorPermission->description = 'Manage Operator Permission';
        $auth->add($manageOperatorPermission);
        $auth->addChild($manager, $manageOperatorPermission);
        
        // Permission: manage_forum
        $manageForumPermission = $auth->createPermission('manage_forum');
        $manageForumPermission->description = 'Manage Operator Permission';
        $auth->add($manageForumPermission);
        $auth->addChild($moderator, $manageForumPermission);

        // assign
        $auth->assign($system, $user->id);
    }
}