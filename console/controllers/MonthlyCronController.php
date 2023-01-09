<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Tracking;

class MonthlyCronController extends Controller
{
    public function actionCleanOrderImage()
    {
        $track = new Tracking();
        $track->description = sprintf("Run CleanOrderImageForm at %s", date('Y-m-d H:i:s'));
        $form = new \console\forms\CleanOrderImageForm(['durationMonth' => 3]);
        $track->save();
        return $form->run();
    }

    // php yii monthly-cron/calculate-customer-tracker
    public function actionCalculateCustomerTracker()
    {
        $year = date('Y', strtotime('last month'));
        $month = date('m', strtotime('last month'));
        $track = new Tracking();
        $track->description = sprintf("Run actionCalculateCustomerTracker at %s", date('Y-m-d H:i:s'));
        $track->save();
        $trackers = \common\models\CustomerTracker::find()->select(['id'])->all();
        foreach ($trackers as $tracker) {
            $form = new \common\forms\CalculateCustomerTrackerPerformanceForm(['id' => $tracker->id]);
            if (!$form->run()) {
                $errors = $form->getErrors();
                $track = new Tracking();
                $track->description = sprintf("CalculateCustomerTracker failure for %s - %s", $tracker->id, json_encode($errors));
                $track->save();
            }
            
            $periodic = new \common\forms\CollectCustomerTrackerReportForm([
                'id' => $tracker->id,
                'year' => $year,
                'month' => $month,
            ]);
            if (!$periodic->run()) {
                $errors = $periodic->getErrors();
                $track = new Tracking();
                $track->description = sprintf("CollectCustomerTrackerReport failure for %s - %s", $tracker->id, json_encode($errors));
                $track->save();
            }
        }
    }
}