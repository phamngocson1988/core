<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\OrderSupplier;
use backend\models\User;
use yii\data\Pagination;

class ReportCostOrderBySupplier extends Model
{
    public $report_from;
    public $report_to;

    private $_command;
    protected $_page;

    public function createCommand()
    {
        $orderTable = Order::tableName();
        $orderSupplierTable = OrderSupplier::tableName();
        $command = Order::find();
        $command->innerJoin($orderSupplierTable, "$orderSupplierTable.order_id = $orderTable.id");
        $command->where(["$orderTable.status" => Order::STATUS_CONFIRMED]);
        $command->andWhere(["$orderSupplierTable.status" => OrderSupplier::STATUS_CONFIRMED]);
        if ($this->report_from) {
            $command->andWhere(['>=', "$orderTable.confirmed_at", $this->report_from]);
        }
        if ($this->report_to) {
            $command->andWhere(['<=', "$orderTable.confirmed_at", $this->report_to]);
        }
        $command->orderBy(["$orderTable.confirmed_at" => SORT_DESC]);
        $command->with('suppliers');
        return $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        // die($this->_command->createCommand()->getRawSql());
        return $this->_command;
    }

    public function getPage()
    {
        if (!$this->_page) {
            $command = $this->getCommand();
            $pages = new Pagination(['totalCount' => $command->count()]);
            $this->_page = $pages;
        }
        return $this->_page;
    }

    public function getOrders()
    {
        $command = $this->getCommand();
        $pages = $this->getPage();
        return $command->offset($pages->offset)->limit($pages->limit)->all();
    }

    public function getReport()
    {
        $orders = $this->getOrders();
        foreach ($orders as $order) {
            $suppliers = $order->suppliers;
            $id = $order->id;
            $suppliers = array_filter($suppliers, function($s) {
                return $s->status == orderSupplier::STATUS_CONFIRMED;
            });
            $data[$id] = [
                'id' => $order->id,
                'game_id' => $order->game_id,
                'game_title' => $order->game_title,
                'quantity' => $order->quantity,
                'total_price' => $order->total_price * $order->rate_usd,
                'confirmed_at' => $order->confirmed_at,
                'suppliers' => $suppliers
            ];
        }
        return $data;
    }

}

