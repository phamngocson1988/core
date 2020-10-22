<?php

namespace supplier\forms;

use Yii;
use yii\helpers\ArrayHelper;
use supplier\models\Order;
use supplier\models\OrderSupplier;

class FetchWaitingShopForm extends FetchShopForm
{
    public function init() 
    {
        $this->status = OrderSupplier::STATUS_REQUEST;
    }
    protected function createCommand()
    {
        $command = OrderSupplier::find();
        $table = Order::tableName();
        $supplierTable = OrderSupplier::tableName();
        $command->innerJoin($table, sprintf("%s.id = %s.order_id", $table, $supplierTable));

        $now = date('Y-m-d H:i:s');
        $command->select([
            "$supplierTable.*", 
            "TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, '$now') as approved_time",
        ]);
        
        $condition = [
            "$supplierTable.order_id" => $this->order_id,
            "$table.game_id" => $this->game_id,
            "$supplierTable.status" => $this->status,
            "$supplierTable.supplier_id" => $this->supplier_id,
        ];
        $condition = array_filter($condition);
        $command->where($condition);

        if ($this->start_date) {
            $command->andWhere(['>=', "$supplierTable.requested_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$supplierTable.requested_at", $this->end_date]);
        }
        // die($command->createCommand()->getRawSql());
        $command->with('order');
        $this->_command = $command;
    }

    public function count()
    {
        return $this->getCommand()->count();
    }

    public function getSumQuantity()
    {
        $supplierTable = OrderSupplier::tableName();
        return $this->getCommand()->sum("$supplierTable.quantity");
    }

    public function getAverageApprovedTime()
    {
        $supplierTable = OrderSupplier::tableName();
        $now = date('Y-m-d H:i:s');
        return $this->getCommand()->average("TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, '$now')");
    }
}
