<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Tracking;
use common\models\Order;

class TestController extends Controller
{
    public function actionIndex()
    {
        // Yii::debug('start tracking');
        // $track = new Tracking();
        // $track->description = sprintf("This log is created by Creator for tracking cronjob: %s::%s", __CLASS__, __METHOD__);
        // $track->save();

        $orders = Order::find()
        ->where(['status' => Order::STATUS_CONFIRMED])
        ->andWhere(['>=', 'confirmed_at', '2022-12-18 00:00:00'])
        ->select(['id'])
        ->asArray()
        ->all();
        print_r($orders);
        $settings = Yii::$app->settings;
        $am = $settings->get('ApplicationSettingForm', 'am_commission_rate');
        $ot = $settings->get('ApplicationSettingForm', 'ot_commission_rate');
        foreach ($orders as $order) {
            $orderId = $order['id'];
            print_r(sprintf("Running order %s", $orderId));
            $form = new \common\forms\RunOrderCommissionForm([
                'order_id' => $orderId,
                'am_commission_rate' => round($am / 100, 2),
                'ot_commission_rate' => round($ot / 100, 2),
            ]);
            $result = $form->run();
            if (!$result) {
                print_r($form->getErrors());
            }    
        }
    }

    public function actionRunCustomerTrackerPeriodic()
    {
        $form = new \common\forms\CollectCustomerTrackerReportForm([
            'id' => 7,
            'year' => 2022,
            'month' => 12,
        ]);
        $form->run();
    }
}