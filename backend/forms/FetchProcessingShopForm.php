<?php

namespace backend\forms;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\OrderSupplier;

class FetchProcessingShopForm extends FetchShopForm
{
    public function init() 
    {
        $this->status = Order::STATUS_PROCESSING;
    }
    protected function createCommand()
    {
        $command = Order::find();
        $table = Order::tableName();
        $supplierTable = OrderSupplier::tableName();

        $now = date('Y-m-d H:i:s');
        $command->select([
            "$table.*", 
            "TIMESTAMPDIFF(MINUTE , $table.created_at, '$now') as waiting_time",
            "TIMESTAMPDIFF(MINUTE , IFNULL($table.approved_at, $table.pending_at), $table.processing_at) as login_time", 
            "TIMESTAMPDIFF(MINUTE , $table.processing_at, '$now') as processing_time",
        ]);
        
        $condition = [
            "$table.id" => $this->id,
            "$table.customer_id" => $this->customer_id,
            "$table.saler_id" => $this->saler_id,
            "$table.orderteam_id" => $this->orderteam_id,
            "$table.payment_method" => $this->payment_method,
            "$table.game_id" => $this->game_id,
            "$table.status" => $this->status,
        ];
        $condition = array_filter($condition);
        $command->where($condition);
        $command->andWhere(["IS", "$table.state", null]);

        if ($this->supplier_id) {
            $command->leftJoin($supplierTable, "{$table}.id = {$supplierTable}.order_id");
            $command->andWhere(["IN", "$supplierTable.status", [
                OrderSupplier::STATUS_REQUEST,
                OrderSupplier::STATUS_APPROVE,
                OrderSupplier::STATUS_PROCESSING,
            ]]);
            $command->andWhere(["{$supplierTable}.supplier_id" => $this->supplier_id]);
        }
        if ($this->start_date) {
            $command->andWhere(['>=', "$table.created_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$table.created_at", $this->end_date]);
        }
        // die($command->createCommand()->getRawSql());
        $this->_command = $command;
    }

    public function count()
    {
        return $this->getCommand()->count();
    }

    public function getSumQuantity()
    {
        $table = Order::tableName();
        return $this->getCommand()->sum("$table.quantity");
    }

    public function getAverageWaitingTime()
    {
        $table = Order::tableName();
        $now = date('Y-m-d H:i:s');
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.pending_at, '$now')");
    }

    public function getAverageLoginTime()
    {
        $table = Order::tableName();
        $now = date('Y-m-d H:i:s');
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , IFNULL($table.approved_at, $table.pending_at), $table.processing_at)");
    }

    public function getAverageProcessingTime()
    {
        $table = Order::tableName();
        $now = date('Y-m-d H:i:s');
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.processing_at, '$now')");
    }
}
