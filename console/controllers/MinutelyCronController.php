<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Tracking;

class MinutelyCronController extends Controller
{
    public function actionDispatcher()
    {
        $track = new Tracking();
        $track->description = sprintf("Run dispatcher order at %s", date('Y-m-d H:i:s'));
        $form = new \console\forms\OrderDispatcherForm();
        return $form->run();
    }

    public function actionRetaken()
    {
        $track = new Tracking();
        $track->description = sprintf("Run Retaken order at %s", date('Y-m-d H:i:s'));
        $form = new \console\forms\OrderRetakenForm();
        return $form->run();
    }
}