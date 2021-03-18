<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use backend\models\Supplier;
use backend\models\SupplierWallet;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;

class ReportSupplierBalanceForm extends Model
{
    public $supplier_id;
    public $report_from;
    public $report_to;

    protected $_command;
    protected $_page;
    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }

    // public function createCommand()
    // {
    //     $command = Supplier::find()->select(['user_id'])->asArray();
    //     if ($this->supplier_id) $command->where(['user_id' => $this->supplier_id]);
    //     return $command;
    // }
    public function createCommand()
    {
        $supplierTable = Supplier::tableName();
        $supplierWalletTable = SupplierWallet::tableName();

        $command = SupplierWallet::find()
        ->select([
            "$supplierTable.user_id AS id", 
            "IFNULL(SUM( $supplierWalletTable.amount ), 0) AS ending_total",
        ])
        ->leftJoin($supplierTable, "$supplierWalletTable.supplier_id = $supplierTable.user_id")
        ->groupBy(["$supplierTable.user_id"])
        ->orderBy(["ending_total" => SORT_DESC])
        ->asArray()
        ->indexBy("id")
        ->where(["$supplierWalletTable.status" => SupplierWallet::STATUS_COMPLETED])
        ;
        if ($this->supplier_id) {
            $command->andWhere(["$supplierTable.user_id" => $this->supplier_id]);
        }
        if ($this->report_to) {
            $command->andWhere(['<=', "$supplierWalletTable.updated_at", $this->report_to]);
        }
        return $command;
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

    public function getUserInformation($userIds) 
    {
        return User::find()->where(['id' => $userIds])->select(["id", "name"])->indexBy("id")->asArray()->all();
    }

    public function getPeriodTotal($userIds)
    {
        $supplierTable = Supplier::tableName();
        $supplierWalletTable = SupplierWallet::tableName();

        $command = SupplierWallet::find()
        ->select([
            "$supplierTable.user_id AS id", 
            "IFNULL(SUM( IF ($supplierWalletTable.type = 'I', $supplierWalletTable.amount, 0) ), 0) AS period_income",
            "IFNULL(SUM( IF ($supplierWalletTable.type = 'O', $supplierWalletTable.amount, 0) ), 0) AS period_outcome",
        ])
        ->leftJoin($supplierTable, "$supplierWalletTable.supplier_id = $supplierTable.user_id")
        ->groupBy(["$supplierTable.user_id"])
        ->asArray()
        ->indexBy("id")
        ->where([
            "$supplierWalletTable.status" => SupplierWallet::STATUS_COMPLETED,
            "$supplierTable.user_id" => $userIds
        ])
        ;
        if ($this->report_from) {
            $command->andWhere(['>=', "$supplierWalletTable.updated_at", $this->report_from]);
        }
        if ($this->report_to) {
            $command->andWhere(['<=', "$supplierWalletTable.updated_at", $this->report_to]);
        }
        // die($command->createCommand()->getRawSql());
        $result = $command->all();
        return $result;
    }

    public function getBeginningTotal($userIds)
    {
        $defaultData = [];
        foreach ($userIds as $userId) {
            $defaultData[$userId] = ['beginning_total' => 0];
        }
        if (!$this->report_from) {
            return $defaultData;
        }

        $supplierTable = Supplier::tableName();
        $supplierWalletTable = SupplierWallet::tableName();

        $command = SupplierWallet::find()
        ->select([
            "$supplierTable.user_id AS id", 
            "IFNULL(SUM( $supplierWalletTable.amount ), 0) AS beginning_total",
        ])
        ->leftJoin($supplierTable, "$supplierWalletTable.supplier_id = $supplierTable.user_id")
        ->groupBy(["$supplierTable.user_id"])
        ->asArray()
        ->indexBy("id")
        ->where([
            "$supplierWalletTable.status" => SupplierWallet::STATUS_COMPLETED,
            "$supplierTable.user_id" => $userIds
        ])
        ;
        if ($this->report_from) {
            $command->andWhere(['<', "$supplierWalletTable.updated_at", $this->report_from]);
        }
        return $command->all();
    }

    public function getTotalIncome()
    {
        $supplierWalletTable = SupplierWallet::tableName();
        $command = SupplierWallet::find()->where([
            "$supplierWalletTable.status" => SupplierWallet::STATUS_COMPLETED,
            "$supplierWalletTable.type" => SupplierWallet::TYPE_INPUT,
        ]);
        if ($this->supplier_id) {
            $command->andWhere(["$supplierWalletTable.supplier_id" => $this->supplier_id]);
        }
        if ($this->report_from) {
            $command->andWhere(['>=', "$supplierWalletTable.updated_at", $this->report_from]);
        }
        if ($this->report_to) {
            $command->andWhere(['<=', "$supplierWalletTable.updated_at", $this->report_to]);
        }
        return $command->sum("$supplierWalletTable.amount");
    }

    public function getTotalOutcome()
    {
        $supplierWalletTable = SupplierWallet::tableName();
        $command = SupplierWallet::find()->where([
            "$supplierWalletTable.status" => SupplierWallet::STATUS_COMPLETED,
            "$supplierWalletTable.type" => SupplierWallet::TYPE_OUTPUT,
        ]);
        if ($this->supplier_id) {
            $command->andWhere(["$supplierWalletTable.supplier_id" => $this->supplier_id]);
        }
        if ($this->report_from) {
            $command->andWhere(['>=', "$supplierWalletTable.updated_at", $this->report_from]);
        }
        if ($this->report_to) {
            $command->andWhere(['<=', "$supplierWalletTable.updated_at", $this->report_to]);
        }
        return $command->sum("$supplierWalletTable.amount");
    }

    public function getTotalEndding()
    {
        $supplierWalletTable = SupplierWallet::tableName();
        $command = SupplierWallet::find()->where([
            "$supplierWalletTable.status" => SupplierWallet::STATUS_COMPLETED,
        ]);
        if ($this->supplier_id) {
            $command->andWhere(["$supplierWalletTable.supplier_id" => $this->supplier_id]);
        }
        if ($this->report_to) {
            $command->andWhere(['<=', "$supplierWalletTable.updated_at", $this->report_to]);
        }
        return $command->sum("$supplierWalletTable.amount");
    }

    // public function getSuppliers()
    // {
    //     $command = $this->getCommand();
    //     $pages = $this->getPage();
    //     return $command->offset($pages->offset)->limit($pages->limit)->all();
    // }
    /**
     * @return array [
     *  'id' => [
     *      'name' => string,
     *      'period_income' => string,
     *      'period_outcome' => string,
     *      'beginning_total' => string,
     *      'ending_total' => string,
     *  ],
     *  ...
     * ]
     */
    public function getReport()
    {
        $command = $this->getCommand();
        $pages = $this->getPage();
        $endingTotal = $command->offset($pages->offset)->limit($pages->limit)->all();
        $userIds = array_keys($endingTotal);

        $userInfor = $this->getUserInformation($userIds);
        $periodTotal = $this->getPeriodTotal($userIds);
        $beginningTotal = $this->getBeginningTotal($userIds);
       
        $data = [];
        $template = [
            'name' => '',
            'period_income' => 0,
            'period_outcome' => 0,
            'beginning_total' => 0,
            'ending_total' => 0,
        ];
        foreach ($endingTotal as $key => $ending) {
            $user = ArrayHelper::getValue($userInfor, $key, []);
            $period = ArrayHelper::getValue($periodTotal, $key, []);
            $beginning = ArrayHelper::getValue($beginningTotal, $key, []);
            $data[$key] = array_merge($template, $ending, $user, $period, $beginning);
        }
        // echo '<pre>';
        // print_r($periodTotal);die;
        return $data;
        // income/outcome
    }
    // public function getReport()
    // {
    //     $suppliers = $this->getSuppliers();
    //     $ids = array_column($suppliers, 'user_id');

    //     // Suppliers
    //     $suppliers = User::find()->where(['in', 'id', $ids])->select(['id', 'name'])->asArray()->all();
    //     $suppliers = array_column($suppliers, 'name', 'id');

    //     // Balance in period
    //     $periodCommand = SupplierWallet::find()->where(['IN', 'supplier_id', $ids]);
    //     $periodCommand->select(['supplier_id', 'type', 'SUM(amount) as amount']);
    //     if ($this->report_from) {
    //         $periodCommand->andWhere(['>=', "created_at", $this->report_from]);
    //     }
    //     if ($this->report_to) {
    //         $periodCommand->andWhere(['<=', "created_at", $this->report_to]);
    //     }
    //     $periodCommand->groupBy(['supplier_id', 'type']);
    //     $periodData = $periodCommand->asArray()->all();
    //     $periodInComeData = array_filter($periodData, function($row) {
    //         return $row['type'] == SupplierWallet::TYPE_INPUT;
    //     });
    //     $periodInComeData = array_column($periodInComeData, 'amount', 'supplier_id');

    //     $periodOutComeData = array_filter($periodData, function($row) {
    //         return $row['type'] == SupplierWallet::TYPE_OUTPUT;
    //     });
    //     $periodOutComeData = array_column($periodOutComeData, 'amount', 'supplier_id');

    //     // Balance from beginning
    //     $beginCommand = SupplierWallet::find()->where(['IN', 'supplier_id', $ids]);
    //     $beginCommand->select(['supplier_id', 'SUM(amount) as amount']);
    //     if ($this->report_from) {
    //         $beginCommand->andWhere(['<', "created_at", $this->report_from]);
    //         $beginCommand->groupBy(['supplier_id']);
    //         $beginData = $beginCommand->asArray()->all();
    //         $beginData = array_column($beginData, 'amount', 'supplier_id');
    //     } else {
    //         $beginData = [];
    //     }
        

    //     // Balance from end
    //     $endCommand = SupplierWallet::find()->where(['IN', 'supplier_id', $ids]);
    //     $endCommand->select(['supplier_id', 'SUM(amount) as amount']);
    //     if ($this->report_to) {
    //         $endCommand->andWhere(['<=', "created_at", $this->report_to]);
    //     }
    //     $endCommand->groupBy(['supplier_id']);
    //     $endData = $endCommand->asArray()->all();
    //     $endData = array_column($endData, 'amount', 'supplier_id');

    //     $data = [];
    //     foreach ($ids as $id) {
    //         $data[$id] = [
    //             'name' => ArrayHelper::getValue($suppliers, $id, ''),
    //             'period_income' => ArrayHelper::getValue($periodInComeData, $id, 0),
    //             'period_outcome' => ArrayHelper::getValue($periodOutComeData, $id, 0),
    //             'beginning_total' => ArrayHelper::getValue($beginData, $id, 0),
    //             'ending_total' => ArrayHelper::getValue($endData, $id, 0),
    //         ];
    //     }
    //     return $data;
    // }

    public function getCustomer()
    {
        if ($this->supplier_id) {
            return User::findOne($this->supplier_id);
        }
        return null;
    }
}
