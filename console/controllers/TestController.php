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
        foreach ($orders as $order) {
            $orderId = $order['id'];
            print_r(sprintf("Running order %s", $orderId));
            $form = new \common\forms\RunOrderCommissionForm(['order_id' => $orderId]);
            $result = $form->run();
            if (!$result) {
                print_r($form->getErrors());
            }    
        }
    }
}