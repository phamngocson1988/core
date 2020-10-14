<?php

namespace backend\forms;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\OrderSupplier;

class FetchConfirmedShopForm extends FetchShopForm
{
    public function init() 
    {
        $this->status = Order::STATUS_CONFIRMED;
    }
    protected function createCommand()
    {
        $command = Order::find();
        $table = Order::tableName();
        $supplierTable = OrderSupplier::tableName();

        $command->leftJoin($supplierTable, sprintf("%s.id = %s.order_id AND %s.status IN ('%s')", $table, $supplierTable, $supplierTable, OrderSupplier::STATUS_CONFIRMED));

        $now = date('Y-m-d H:i:s');
        $command->select([
            "$table.id", 
            "$table.customer_id", 
            "$table.customer_name", 
            "$table.game_id", 
            "$table.game_title", 
            "$table.created_at", 
            "$table.state", 
            "$table.status", 
            "$table.saler_id", 
            "$table.orderteam_id", 
            "$supplierTable.completed_at", 
            "$supplierTable.confirmed_at", 
            "$supplierTable.doing as quantity",
            "$supplierTable.supplier_id as supplier_id", 

            "TIMESTAMPDIFF(MINUTE , $table.created_at, $table.completed_at) as completed_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.completed_at) as supplier_completed_time",
            "TIMESTAMPDIFF(MINUTE , $table.created_at, $table.pending_at) as pending_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at) as approved_time", 
            "TIMESTAMPDIFF(MINUTE , $supplierTable.processing_at, $supplierTable.completed_at) as processing_time", 
            "TIMESTAMPDIFF(MINUTE , $supplierTable.completed_at, $supplierTable.confirmed_at) as confirmed_time",
            
        ]);
        
        $condition = [
            "$table.id" => $this->id,
            "$table.customer_id" => $this->customer_id,
            "$table.saler_id" => $this->saler_id,
            "$table.orderteam_id" => $this->orderteam_id,
            "$table.payment_method" => $this->payment_method,
            "$table.game_id" => $this->game_id,
            "$table.status" => $this->status,
            "$supplierTable.supplier_id" => $this->supplier_id
        ];
        $condition = array_filter($condition);
        $command->where($condition);
        $command->andWhere(["IS", "$table.state", null]);

        if ($this->start_date) {
            $command->andWhere(['>=', "$table.confirmed_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$table.confirmed_at", $this->end_date]);
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

    public function getAverage()
    {
        $supplierTable = OrderSupplier::tableName();
        $table = Order::tableName();
        return $this->getCommand()->select([
            "COUNT(*) as count",
            "SUM($supplierTable.quantity) as quantity",
            "AVG(TIMESTAMPDIFF(MINUTE , $table.created_at, $table.completed_at)) as completed_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.completed_at)) as completed_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.completed_at)) as supplier_completed_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $table.created_at, $table.pending_at)) as pending_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at)) as approved_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.processing_at, $supplierTable.completed_at)) as processing_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.completed_at, $supplierTable.confirmed_at)) as confirmed_time",
        ])->asArray()->one();
    }

    public function getSumQuantity()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->sum("$supplierTable.quantity");
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

    public function getAverageConfirmedTime()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.completed_at, $supplierTable.confirmed_at)");
    }
}
