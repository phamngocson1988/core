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
            $form->dispatch();
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
        ->asArray()
        ->all();
        $orderIds = ArrayHelper::getColumn($orders, 'id');
        return $orderIds;
    }
}
