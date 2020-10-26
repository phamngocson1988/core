<?php

namespace supplier\forms;

use Yii;
use yii\helpers\ArrayHelper;
use supplier\models\Order;
use supplier\models\OrderSupplier;

class FetchPendingShopForm extends FetchShopForm
{
    protected function createCommand()
    {
        $command = OrderSupplier::find();
        $table = Order::tableName();
        $supplierTable = OrderSupplier::tableName();
        $command->innerJoin($table, sprintf("%s.id = %s.order_id", $table, $supplierTable));

        $now = date('Y-m-d H:i:s');
        $command->select([
            "$supplierTable.*", 
            "TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, '$now') as pending_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at) as approved_time",
            "TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, '$now') as login_time",
        ]);
        
        $condition = [
            "$supplierTable.order_id" => $this->order_id,
            "$table.game_id" => $this->game_id,
            "$supplierTable.status" => OrderSupplier::STATUS_APPROVE,
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

        if ($this->status) {
            if ($this->status != OrderSupplier::STATUS_APPROVE) {
                $command->andWhere(["{$table}.state" => $this->status]);
            } else {
                $command->andWhere(["{$table}.state" => null]);
            }
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
        $now = date('Y-m-d H:i:s');
        $command = clone $this->getCommand();
        return $command->select([
            "$supplierTable.id",
            "$supplierTable.order_id",
            "COUNT(*) as count",
            "SUM($supplierTable.quantity) as quantity",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, '$now')) as pending_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.requested_at, $supplierTable.approved_at)) as approved_time",
            "AVG(TIMESTAMPDIFF(MINUTE , $supplierTable.approved_at, '$now')) as login_time",
        ])->asArray()->one();
    }
}
