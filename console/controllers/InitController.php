<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Tracking;

class InitController extends Controller
{
    // php yii init/init
    public function actionInit()
    {
        // $auth = Yii::$app->authManager;
        // $auth->removeAll();

        // // Role: admin
        // $admin = $auth->createRole('admin');
        // $admin->description = 'Admin';
        // $auth->add($admin);

        // // User: admin
        // $form = new \backend\forms\SignupForm([
        //     'name' => 'Administrator',
        //     'username' => 'admin',
        //     'email' => 'phamngocson1988@gmail.com',
        //     'password' => '123456'
        // ]);
        // $form->signup();
    }

    public function actionIndex()
    {
        // Yii::debug('start tracking');
        $track = new Tracking();
        $track->description = sprintf("This log is created by Creator for tracking cronjob: %s::%s", __CLASS__, __METHOD__);
        $track->save();
    }
}