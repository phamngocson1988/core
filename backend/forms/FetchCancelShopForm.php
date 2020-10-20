<?php

namespace backend\forms;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\OrderSupplier;

class FetchCancelShopForm extends FetchShopForm
{
    protected function createCommand()
    {
        $command = Order::find();
        $table = Order::tableName();

        $now = date('Y-m-d H:i:s');
        $command->select([
            "$table.id", 
            "$table.customer_id", 
            "$table.customer_name",
            "$table.quantity",
            "$table.doing_unit", 
            "$table.game_id", 
            "$table.game_title", 
            "$table.created_at", 
            "$table.completed_at", 
            "$table.state", 
            "$table.status", 
            "$table.saler_id", 
            "$table.orderteam_id", 
            "$table.request_cancel_description",
            "$table.request_cancel_time",
            "$table.request_cancel",
        ]);
        
        $condition = [
            "$table.id" => $this->id,
            "$table.customer_id" => $this->customer_id,
            "$table.saler_id" => $this->saler_id,
            "$table.orderteam_id" => $this->orderteam_id,
            "$table.payment_method" => $this->payment_method,
            "$table.game_id" => $this->game_id,
        ];
        $condition = array_filter($condition);
        $command->where($condition);
        $command->andWhere(['NOT IN', "$table.status", [
            Order::STATUS_COMPLETED,
            Order::STATUS_CONFIRMED,
            Order::STATUS_DELETED,
        ]]);
        $command->andWhere(['OR',
            ["$table.status" => Order::STATUS_CANCELLED],
            ["$table.request_cancel" => 1]
        ]);

        if ($this->start_date) {
            $command->andWhere(['>=', "$table.created_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$table.created_at", $this->end_date]);
        }
        $command->indexBy(function ($row) use(&$index){
           return ++$index;
        });
        // die($command->createCommand()->getRawSql());
        $this->_command = $command;
    }

    public function count()
    {
        return $this->getCommand()->count();
    }

    public function countCancelling() 
    {
        $table = Order::tableName();
        $command = Order::find()->where(["$table.request_cancel" => 1]);
        $command->andWhere(['NOT IN', "$table.status", [
            Order::STATUS_COMPLETED,
            Order::STATUS_CONFIRMED,
            Order::STATUS_DELETED,
            Order::STATUS_CANCELLED
        ]]);
        return $command->count();
    }

    public function getSumQuantity()
    {
        $table = Order::tableName();
        return $this->getCommand()->sum("$table.quantity");
    }

    public function getAverageCompletedTime()
    {
        $table = Order::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.created_at, $table.completed_at)");
    }

    public function getAverageSupplierCompletedTime()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.completed_at)");
    }

    public function getAveragePendingTime()
    {
        $table = Order::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.created_at, $table.pending_at)");
    }

    public function getAverageApprovedTime()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at)");
    }

    public function getAverageProcessingTime()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.processing_at, $supplierTable.completed_at)");
    }

    public function getAverageWaitingTime()
    {
        $table = Order::tableName();
        $now = date('Y-m-d H:i:s');
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.created_at, IFNULL($table.processing_at, '$now'))");
    }

    public function getAverageDistributedTime()
    {
        $table = Order::tableName();
        $now = date('Y-m-d H:i:s');
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.pending_at, IFNULL($table.distributed_at, '$now'))");
    }

}
