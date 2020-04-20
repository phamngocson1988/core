<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\OrderSupplier;
use backend\models\User;
use backend\models\UserReseller;
use backend\models\Order;
use yii\data\Pagination;

class ReportCostOrderByReseller extends Model
{
    public $reseller_id;

    private $_command;
    protected $_page;

    public function createCommand()
    {
        $resellerTable = UserReseller::tableName();
        $orderTable = Order::tableName();
        $supplierTable = OrderSupplier::tableName();
        $supplierStatusConfirmed = OrderSupplier::STATUS_CONFIRMED;
        $command = Order::find();
        $command->innerJoin($resellerTable, "$resellerTable.user_id = $orderTable.customer_id");
        $command->innerJoin($supplierTable, "$supplierTable.order_id = $orderTable.id AND $supplierTable.status = '$supplierStatusConfirmed'");
        $command->where(["$orderTable.status" => Order::STATUS_CONFIRMED]);
        if ($this->reseller_id) {
            $command->andWhere(["$orderTable.customer_id" => $this->reseller_id]);
        }
        $command->groupBy(["$orderTable.customer_id"]);
        $command->select([
            "$orderTable.customer_id as customer_id",
            "$orderTable.customer_name as customer_name",
            "SUM($supplierTable.doing) as quantity",
            "SUM($orderTable.total_price * $supplierTable.doing * $orderTable.rate_usd / $supplierTable.quantity) as total_revenue",
            "SUM($supplierTable.total_price) as total_cost",
            "SUM(($orderTable.total_price * $supplierTable.doing * $orderTable.rate_usd / $supplierTable.quantity) - $supplierTable.total_price) as total_profit",
        ]);
        $command->orderBy(["total_revenue" => SORT_DESC]);
        $command->asArray();
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

    public function getReport()
    {
        $command = $this->getCommand();
        $pages = $this->getPage();
        return $command->offset($pages->offset)->limit($pages->limit)->all();
    }

    public function fetchResellers()
    {
        $userTable = User::tableName();
        $resellerTable = UserReseller::tableName();

        $users = User::find()->innerJoin($resellerTable, "$userTable.id = $resellerTable.user_id")->select(["$userTable.id", "$userTable.name"])->orderBy(["$userTable.name" => SORT_ASC])->all();

        return ArrayHelper::map($users, 'id', 'name');
    }
}

