<?php

namespace console\forms;

use Yii;
use common\models\Order;
use common\models\OrderSupplier;
use common\models\Game;
use yii\helpers\ArrayHelper;

class OrderDispatcherForm extends ActionForm
{
    /**
     * TODO: 
     * - Fetch un-assigned orders
     * - Check the game is using auto dispatcher or not
     * - Fetch subscribed suppliers for each game and calcualte the level
     * - Assign to approciate supplier automaticall
     */

    public function run() 
    {
        $orderIds = $this->fetchDispatchOrder();
        if (!count($orderIds)) return true;

        foreach ($orderIds as $orderId) {
            $form = new DispatchOrderForm(['id' => $orderId]);
            if (!$form->dispatch()) {
                $order = $form->getOrder();
                $errors = $form->getErrors();
                $order->log(sprintf("Dispatch order %s fail: %s", $order->id, json_encode($errors)));
            } else {
                $order = $form->getOrder();
                $order->log(sprintf("Dispatch order %s success", $order->id));
            }
        }
        return true;
    }
    
    protected function fetchDispatchOrder()
    {
        $games = Game::find()
        ->select(['id'])
        ->asArray()
        ->where(['auto_dispatcher' => Game::AUTO_DISPATCHER_ON])
        ->all();
        $gameIds = ArrayHelper::getColumn($games, 'id');
        if (!count($gameIds)) return [];

        $orders = Order::find()
        ->select(['id'])
        ->where([
            'status' => [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_PARTIAL],
            'game_id' => $gameIds
        ])
        ->andWhere(['IS', 'state', new \yii\db\Expression('null')])
        ->asArray()
        ->all();
        $orderIds = ArrayHelper::getColumn($orders, 'id');
        return $orderIds;
    }
}
