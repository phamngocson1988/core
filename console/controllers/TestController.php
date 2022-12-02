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
        // $track = new Tracking();
        // $track->description = sprintf("This log is created by Creator for tracking cronjob: %s::%s", __CLASS__, __METHOD__);
        // $track->save();

        $orderId = 3;
        $form = new \backend\forms\ConvertLeadTrackerToCustomerForm(['id' => $orderId]);
        $result = $form->convert();
        if (!$result) {
            print_r($form->getErrors());
        }
    }
}