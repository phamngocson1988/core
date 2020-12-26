<?php

namespace console\forms;

use Yii;
use common\models\Order;
use common\models\OrderSupplier;
use common\models\Game;
use common\models\Tracking;
use yii\helpers\ArrayHelper;

class OrderRetakenForm extends ActionForm
{
    public $waiting_time = 30; // minutes
    /**
     * TODO: 
     * - Fetch un-assigned orders
     * - Check the game is using auto dispatcher or not
     * - Fetch subscribed suppliers for each game and calcualte the level
     * - Assign to approciate supplier automaticall
     */

    public function run() 
    {
        // fetch orders
        $requestedOrders = $this->fetchOrders();
        print_r($requestedOrders);
        if (!count($requestedOrders)) return true;
        foreach ($requestedOrders as $orderId) {
            $form = new RetakeOrderSupplierForm(['order_id' => $orderId]);
            if ($form->validate()) {
                print_r("Validate ok order " . $orderId);
                $form->retake();
            } else {
                $errors = $form->getFirstErrors();
                $track = new Tracking();
                $error = reset($errors);
                print_r("Validate fali order $orderId with error: $error");
                $track->description = sprintf("Console OrderRetakenForm Fail %s: %s", $orderId, $error);
                $track->save();
            }
        }
        return true;
    }
    
    /**
     * @return [['id' => 1, 'game_id', 1], ...]
     */
    protected function fetchOrders()
    {
        $requestedTime = date('Y-m-d H:i:s', strtotime(sprintf("-%s minutes", (int)$this->waiting_time)));
        $supplierOrders = OrderSupplier::find()
        // was sent to supplier by system
        ->where(['status' => OrderSupplier::STATUS_REQUEST])
        ->andWhere(['IS', 'requested_by', new \yii\db\Expression('null')])
        // time was requested is more than 30 minutes
        ->andWhere(["<", "requested_at", $requestedTime])
        ->select(['order_id'])
        ->asArray()
        ->all();
        return ArrayHelper::getColumn($supplierOrders, 'order_id');
    }    
}
