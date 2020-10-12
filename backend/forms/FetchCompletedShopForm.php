<?php

namespace backend\forms;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\OrderSupplier;

class FetchCompletedShopForm extends FetchShopForm
{
    public function init() 
    {
        $this->status = Order::STATUS_COMPLETED;
    }
    protected function createCommand()
    {
        $command = Order::find();
        $table = Order::tableName();
        $supplierTable = OrderSupplier::tableName();

        $command->leftJoin($supplierTable, sprintf("%s.id = %s.order_id AND %s.status IN ('%s')", $table, $supplierTable, $supplierTable, OrderSupplier::STATUS_COMPLETED));

        $now = date('Y-m-d H:i:s');
        $command->select([
            "$table.*", 
            "TIMESTAMPDIFF(MINUTE , $table.created_at, $table.completed_at) as completed_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.completed_at) as supplier_completed_time",
            

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
