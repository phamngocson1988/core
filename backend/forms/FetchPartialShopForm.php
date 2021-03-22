<?php

namespace backend\forms;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\OrderSupplier;

class FetchPartialShopForm extends FetchShopForm
{
    public function init() 
    {
        $this->status = Order::STATUS_PARTIAL;
    }
    protected function createCommand()
    {
        $command = Order::find();
        $table = Order::tableName();
        $supplierTable = OrderSupplier::tableName();

        $command->leftJoin($supplierTable, sprintf("%s.id = %s.order_id AND %s.status IN ('%s', '%s', '%s')", $table, $supplierTable, $supplierTable, OrderSupplier::STATUS_REQUEST, OrderSupplier::STATUS_APPROVE, OrderSupplier::STATUS_PROCESSING));

        $now = date('Y-m-d H:i:s');
        $command->select([
            "$table.id", 
            "$table.customer_id", 
            "$table.customer_name", 
            "$table.game_id", 
            "$table.game_title", 
            "IFNULL($supplierTable.quantity, $table.quantity - $table.doing_unit) as quantity", 
            // "$supplierTable.doing as doing_unit", 
            
            "$table.cogs_price",
            "$table.price",
            "$table.rate_usd",
            "$table.request_cancel",
            "$table.doing_unit",
            "$table.saler_id", 
            "$table.status", 
            "$table.state", 
            "$table.orderteam_id", 
            "TIMESTAMPDIFF(MINUTE , $table.created_at, IFNULL($supplierTable.processing_at, '$now')) as waiting_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at) as approved_time", 
            "(TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.processing_at) / $supplierTable.quantity) as login_time", 
            "TIMESTAMPDIFF(MINUTE , $supplierTable.processing_at, '$now') as processing_time",
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
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->sum("IFNULL($supplierTable.quantity, $table.quantity - $table.doing_unit)");
    }

    public function getAverageWaitingTime()
    {
        $table = Order::tableName();
        $supplierTable = OrderSupplier::tableName();
        $now = date('Y-m-d H:i:s');
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $table.created_at, IFNULL($supplierTable.processing_at, '$now'))");
    }

    public function getAverageApprovedTime()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at)");
    }

    public function getAverageProcessingTime()
    {
        $supplierTable = OrderSupplier::tableName();
        $now = date('Y-m-d H:i:s');
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.processing_at, '$now')");
    }

    public function getAverageLoginTime()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.processing_at) / $supplierTable.quantity");
    }
}
