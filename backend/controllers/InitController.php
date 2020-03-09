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
                        'allow' => false,
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

        // Role: admin
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);

        // User: admin
        $form = new \backend\forms\SignupForm([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'phamngocson1988@gmail.com',
            'password' => '123456'
        ]);
        $user = $form->signup();
        if ($user) {
            $auth->assign($admin, $user->id);
            die('done');
        } else {
            die('fail');
        }
    }
}