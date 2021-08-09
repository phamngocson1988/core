<?php

namespace supplier\forms;

use Yii;
use yii\helpers\ArrayHelper;
use supplier\models\Order;
use supplier\models\OrderSupplier;

class FetchConfirmedShopForm extends FetchShopForm
{
    public function init() 
    {
        $this->status = OrderSupplier::STATUS_CONFIRMED;
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
            "TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.completed_at) as completed_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at) as approved_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.processing_at) as login_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.processing_at, $supplierTable.completed_at) as processing_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.completed_at, $supplierTable.confirmed_at) as confirmed_time",
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
            $command->andWhere(['>=', "$supplierTable.confirmed_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$supplierTable.confirmed_at", $this->end_date]);
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

    public function getSummary()
    {
        $supplierTable = OrderSupplier::tableName();
        // $command = clone $this->getCommand();
        return $this->getCommand()->select([
            "$supplierTable.order_id",
            "COUNT(*) as count",
            "SUM($supplierTable.quantity) as quantity",
            "SUM($supplierTable.doing) as doing",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.completed_at)) as completed_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at)) as approved_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, $supplierTable.processing_at)) as login_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.processing_at, $supplierTable.completed_at)) as processing_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.completed_at, $supplierTable.confirmed_at)) as confirmed_time",
        ])->asArray()->one();
    }
}
