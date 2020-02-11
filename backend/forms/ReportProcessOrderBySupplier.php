<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\OrderSupplier;
use backend\models\User;

class ReportProcessOrderBySupplier extends Model
{
    public $supplier_id;
    public $start_date;
    public $end_date;

    protected $_supplier;
    private $_command;

    public function init()
    {
        if (!$this->start_date) $this->start_date = date('Y-m-d 00:00', strtotime('-29 days'));
        if (!$this->end_date) $this->end_date = date('Y-m-d 23:59');
    }

    public function rules()
    {
        return [
            ['supplier_id', 'safe'],
            [['start_date', 'end_date'], 'safe'],
        ];
    }

    public function createCommand()
    {
        $orderTable = Order::tableName();
        $supplierTable = OrderSupplier::tableName();
        $command = Order::find()
        ->innerJoin($supplierTable, "$orderTable.id = $supplierTable.order_id AND $orderTable.supplier_id = $supplierTable.supplier_id")
        ->select(["$orderTable.id", "$supplierTable.quantity", "$orderTable.status", "$orderTable.completed_at", "$supplierTable.total_price"])
        ->where(["iN", "$orderTable.status", [Order::STATUS_COMPLETED, Order::STATUS_CONFIRMED]])  
        ->andWhere(["$supplierTable.status" => OrderSupplier::STATUS_APPROVE])
        ->orderBy(["$orderTable.created_at" => SORT_ASC]);

        if ($this->supplier_id) {
            $command->andWhere(["$supplierTable.supplier_id" => $this->supplier_id]);
        }

        return $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }

    public function getSupplier()
    {
        return User::findOne($this->supplier_id);
    }
}
