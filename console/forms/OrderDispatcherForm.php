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
        // fetch orders
        $unAssignedOrders = $this->fetchOrders();
        if (!count($unAssignedOrders)) return true;
        $gameIds = ArrayHelper::getColumn($unAssignedOrders, 'game_id');
        $autoDispatcherGames = $this->getAutoDispatcherGames($gameIds);
        if (!count($autoDispatcherGames)) return true;

        // filter orders are going to be dispatched
        $validOrders = array_filter($unAssignedOrders, function($order) use ($autoDispatcherGames) {
            return in_array($order['game_id'], $autoDispatcherGames);
        });
        foreach ($validOrders as $validOrder) {
            $form = new DispatchOrderForm(['id' => $validOrder['id']]);
            $form->dispatch();
        }
        return true;
    }
    
    /**
     * @return [['id' => 1, 'game_id', 1], ...]
     */
    protected function fetchOrders()
    {
        $command = Order::find();
        $orderTable = Order::tableName();
        $supplierTable = OrderSupplier::tableName();
        $command->leftJoin($supplierTable, sprintf("%s.id = %s.order_id", $orderTable, $supplierTable));

        $command->select([
            "$orderTable.id", 
            "$orderTable.game_id", 
        ]);
        
        $command->where(["$orderTable.status" => [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_PARTIAL,
        ]]);
        $command->andWhere(['OR',
            ["NOT IN", "$supplierTable.status", [
                OrderSupplier::STATUS_REQUEST,
                OrderSupplier::STATUS_APPROVE,
                OrderSupplier::STATUS_PROCESSING,
                OrderSupplier::STATUS_COMPLETED,
                OrderSupplier::STATUS_CONFIRMED,
                OrderSupplier::STATUS_STOP,
            ]],
            ["IS", "$supplierTable.status", new \yii\db\Expression('null')]
        ]);

        // die($command->createCommand()->getRawSql());
        $command->asArray();
        return $command->all();
    }

    protected function getAutoDispatcherGames($gameIds)
    {
        $games = Game::find()
        ->select(['id'])
        ->asArray()
        ->where([
            'auto_dispatcher' => Game::AUTO_DISPATCHER_ON,
            'id' => $gameIds,
        ])->all();
        return ArrayHelper::getColumn($games, 'id');
    }    
}
