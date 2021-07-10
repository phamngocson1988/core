<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Tracking;

class HourlyCronController extends Controller
{
    // php yii hourly-cron/confirm-order
    public function actionConfirmOrder()
    {
        $now = date('Y-m-d H:i:s');
        Yii::info('start actionPaymentCoinPaidCallback');
        Yii::info(sprintf("Run HourlyCronController actionConfirmOrder at %s", $now));
        $track = new Tracking();
        $form = new \console\forms\AutoConfirmOrderForm();
        $form->run();
        $total = $form->getCountResult();
        $failureIds = $form->getFailureIds();
        $track->description = sprintf("Run HourlyCronController actionConfirmOrder at %s: Run %s - Fail %s", $now, $total, implode('-', $failureIds));
        $track->save();
    }
}