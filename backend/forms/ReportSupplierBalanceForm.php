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

    public function createCommand()
    {
        $command = Supplier::find()->select(['user_id'])->asArray();
        if ($this->supplier_id) $command->where(['user_id' => $this->supplier_id]);
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

    public function getSuppliers()
    {
        $command = $this->getCommand();
        $pages = $this->getPage();
        return $command->offset($pages->offset)->limit($pages->limit)->all();
    }
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
        $suppliers = $this->getSuppliers();
        $ids = array_column($suppliers, 'user_id');

        // Suppliers
        $suppliers = User::find()->where(['in', 'id', $ids])->select(['id', 'name'])->asArray()->all();
        $suppliers = array_column($suppliers, 'name', 'id');

        // Balance in period
        $periodCommand = SupplierWallet::find()->where(['IN', 'supplier_id', $ids]);
        $periodCommand->select(['supplier_id', 'type', 'SUM(amount) as amount']);
        if ($this->report_from) {
            $periodCommand->andWhere(['>=', "created_at", $this->report_from]);
        }
        if ($this->report_to) {
            $periodCommand->andWhere(['<=', "created_at", $this->report_to]);
        }
        $periodCommand->groupBy(['supplier_id', 'type']);
        $periodData = $periodCommand->asArray()->all();
        $periodInComeData = array_filter($periodData, function($row) {
            return $row['type'] == SupplierWallet::TYPE_INPUT;
        });
        $periodInComeData = array_column($periodInComeData, 'amount', 'supplier_id');

        $periodOutComeData = array_filter($periodData, function($row) {
            return $row['type'] == SupplierWallet::TYPE_OUTPUT;
        });
        $periodOutComeData = array_column($periodOutComeData, 'amount', 'supplier_id');

        // Balance from beginning
        $beginCommand = SupplierWallet::find()->where(['IN', 'supplier_id', $ids]);
        $beginCommand->select(['supplier_id', 'SUM(amount) as amount']);
        if ($this->report_from) {
            $beginCommand->andWhere(['<', "created_at", $this->report_from]);
            $beginCommand->groupBy(['supplier_id']);
            $beginData = $beginCommand->asArray()->all();
            $beginData = array_column($beginData, 'amount', 'supplier_id');
        } else {
            $beginData = [];
        }
        

        // Balance from end
        $endCommand = SupplierWallet::find()->where(['IN', 'supplier_id', $ids]);
        $endCommand->select(['supplier_id', 'SUM(amount) as amount']);
        if ($this->report_to) {
            $endCommand->andWhere(['<=', "created_at", $this->report_to]);
        }
        $endCommand->groupBy(['supplier_id']);
        $endData = $endCommand->asArray()->all();
        $endData = array_column($endData, 'amount', 'supplier_id');

        $data = [];
        foreach ($ids as $id) {
            $data[$id] = [
                'name' => ArrayHelper::getValue($suppliers, $id, ''),
                'period_income' => ArrayHelper::getValue($periodInComeData, $id, 0),
                'period_outcome' => ArrayHelper::getValue($periodOutComeData, $id, 0),
                'beginning_total' => ArrayHelper::getValue($beginData, $id, 0),
                'ending_total' => ArrayHelper::getValue($endData, $id, 0),
            ];
        }
        return $data;
    }

    public function getCustomer()
    {
        if ($this->supplier_id) {
            return User::findOne($this->supplier_id);
        }
        return null;
    }
}
