<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\OrderSupplier;
use backend\models\User;
use backend\models\Supplier;
use yii\data\Pagination;

class ReportCostOrderBySupplier extends Model
{
    public $supplier_id;

    private $_command;
    protected $_page;

    public function createCommand()
    {
        $userTable = User::tableName();
        $orderSupplierTable = OrderSupplier::tableName();
        $command = OrderSupplier::find();
        $command->innerJoin($userTable, "$userTable.id = $orderSupplierTable.supplier_id");

        $command->select([
            "$userTable.name as supplier_name",
            "SUM($orderSupplierTable.quantity) as quantity",
            "SUM($orderSupplierTable.total_price) as total_price",
        ]);
        $command->where(["$orderSupplierTable.status" => OrderSupplier::STATUS_CONFIRMED]);
        if ($this->supplier_id) {
            $command->andWhere(["$orderSupplierTable.supplier_id" => $this->supplier_id]);
        }
        $command->groupBy(["$orderSupplierTable.supplier_id"]);
        $command->orderBy(["total_price" => SORT_DESC]);
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

    public function fetchSuppliers()
    {
        $userTable = User::tableName();
        $supplierTable = Supplier::tableName();

        $users = User::find()->innerJoin($supplierTable, "$userTable.id = $supplierTable.user_id")->select(["$userTable.id", "$userTable.name"])->orderBy(["$userTable.name" => SORT_ASC])->all();

        return ArrayHelper::map($users, 'id', 'name');
    }
}

