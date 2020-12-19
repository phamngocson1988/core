<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Tracking;

class TestController extends Controller
{
    public function actionIndex()
    {
        // Yii::debug('start tracking');
        $track = new Tracking();
        $track->description = sprintf("This log is created by Creator for tracking cronjob: %s::%s", __CLASS__, __METHOD__);
        $track->save();
    }
}