<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\SupplierWallet;
use backend\models\Order;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use common\components\helpers\FormatConverter;

class SupplierFastReportForm extends Model
{
    public $report_from;
    public $report_to;

    protected $_command;
    protected $_page;
    protected $_range;

    public function init()
    {
        if (!$this->report_from) $this->report_from = date('Y-m-d', strtotime('-29 days'));
        if (!$this->report_to) $this->report_to = date('Y-m-d');
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }

    // public function getWalletCommand()
    // {
    //     $command = SupplierWallet::find();
    //     $command->select([
    //         'id',
    //         'SUM(amount) as amount',
    //         'type',
    //         'DATE(created_at) as report_date'
    //     ]);
    //     $command->andWhere(['BETWEEN', 'created_at', $this->getDateTimeFrom(), $this->getDateTimeTo()]);
    //     $command->groupBy(['report_date', 'type']);
    //     $command->orderBy(['created_at' => SORT_DESC]);
    //     $command->asArray();
    //     return $command;
    // }

    public function getOrderCommand()
    {
        $command = Order::find()->where(['status' => Order::STATUS_CONFIRMED]);
        $command->select([
            'DATE(confirmed_at) as report_date',
            'SUM(quantity) as quantity',
            'SUM(total_price * rate_usd) as total_price'
        ]);
        $command->andWhere(['BETWEEN', 'confirmed_at', $this->getDateTimeFrom(), $this->getDateTimeTo()]);
        $command->groupBy(['report_date']);
        $command->asArray();
        return $command;
    }

    public function getRange() 
    {
        if (!$this->_range) {
            $this->_range = FormatConverter::getDateRange($this->report_from, $this->report_to);
        }
        return $this->_range;
    }

    public function getPage()
    {
        if (!$this->_page) {
            $range = $this->getRange();
            $this->_page = new Pagination(['totalCount' => count($range)]);
        }
        return $this->_page;
    }
    // public function getReport1()
    // {
    //     $pages = $this->getPage();
    //     $range = $this->getRange();
    //     // wallet report
    //     $walletCommand = $this->getWalletCommand();
    //     $walletData = $walletCommand->offset($pages->offset)->limit($pages->limit)->all();
    //     $income = array_filter($walletData, function($row) {
    //         return $row['type'] == SupplierWallet::TYPE_INPUT;
    //     });
    //     $income = array_column($income, 'amount', 'report_date');
    //     $outcome = array_filter($walletData, function($row) {
    //         return $row['type'] == SupplierWallet::TYPE_OUTPUT;
    //     });
    //     $outcome = array_column($outcome, 'amount', 'report_date');

    //     // order report
    //     $orderCommand = $this->getOrderCommand();
    //     $orders = $orderCommand->all();
    //     $quantity = array_column($orders, 'quantity', 'report_date');
    //     $revenue = array_column($orders, 'total_price', 'report_date');

    //     // build report
    //     $result = [];
    //     $firstIncome = $this->getFirstIncome();
    //     foreach ($range as $date) {
    //         $incomeByDate = ArrayHelper::getValue($income, $date, 0);
    //         $outcomeByDate = ArrayHelper::getValue($outcome, $date, 0);
    //         $quantityByDate = ArrayHelper::getValue($quantity, $date, 0);
    //         $revenueBByDate = ArrayHelper::getValue($revenue, $date, 0);
    //         $lastIncome = $firstIncome + $incomeByDate + $outcomeByDate;
    //         $result[$date] = [
    //             'quantity' => $quantityByDate,
    //             'revenue' => $revenueBByDate,
    //             'first_income' => $firstIncome,
    //             'income' => $incomeByDate,
    //             'outcome' => $outcomeByDate,
    //             'last_income' => $lastIncome
    //         ];
    //         $firstIncome = $lastIncome;
    //     }
    //     return $result;
    // }

    public function getReport()
    {
        $pages = $this->getPage();
        $range = $this->getRange();
        /**
         * Daily income: total supplier incomes each date
         * Daily outcome: total supplier outcomes each date
         * @var float $income
         * @var float $outcome
         */
        $income = $this->getDailyIncome();
        $outcome = $this->getDailyOutcome();

        /**
         * Quantity & revenue: Calculte total quantity and total price for each day
         * @var float $quantity
         * @var float $revenue (in VND) 
         */
        $orderCommand = $this->getOrderCommand();
        $orders = $orderCommand->all();
        $quantity = array_column($orders, 'quantity', 'report_date');
        $revenue = array_column($orders, 'total_price', 'report_date');

        /**
         * First income: total supplier incomes up to begining of report
         * @var float
         */
        $firstIncome = $this->getFirstIncome();

        // build report
        $result = [];
        foreach ($range as $date) {
            $incomeByDate = ArrayHelper::getValue($income, $date, 0);
            $outcomeByDate = ArrayHelper::getValue($outcome, $date, 0);
            $quantityByDate = ArrayHelper::getValue($quantity, $date, 0);
            $revenueBByDate = ArrayHelper::getValue($revenue, $date, 0);
            $lastIncome = $firstIncome + $incomeByDate + $outcomeByDate;
            $result[$date] = [
                'quantity' => $quantityByDate,
                'revenue' => $revenueBByDate,
                'first_income' => $firstIncome,
                'income' => $incomeByDate,
                'outcome' => $outcomeByDate,
                'last_income' => $lastIncome
            ];
            $firstIncome = $lastIncome;
        }
        return $result;
    }

    public function getFirstIncome()
    {
        return SupplierWallet::find()->where(['<', 'created_at', $this->getDateTimeFrom()])->sum('amount');
    }

    public function getDailyIncome()
    {
        $command = SupplierWallet::find();
        $command->select([
            'DATE(created_at) as report_date',
            'SUM(amount) as amount',
        ]);
        $command->andWhere(['BETWEEN', 'created_at', $this->getDateTimeFrom(), $this->getDateTimeTo()]);
        $command->andWhere(['type' => SupplierWallet::TYPE_INPUT]);
        $command->groupBy(['report_date']);
        $command->asArray();
        $data = $command->all();
        return array_column($data, 'amount', 'report_date');
    }

    public function getDailyOutcome()
    {
        $command = SupplierWallet::find();
        $command->select([
            'DATE(created_at) as report_date',
            'SUM(amount) as amount',
        ]);
        $command->andWhere(['BETWEEN', 'created_at', $this->getDateTimeFrom(), $this->getDateTimeTo()]);
        $command->andWhere(['type' => SupplierWallet::TYPE_OUTPUT]);
        $command->groupBy(['report_date']);
        $command->asArray();
        $data = $command->all();
        return array_column($data, 'amount', 'report_date');
    }

    public function getDateTimeFrom()
    {
        return sprintf("%s 00:00:00", $this->report_from);
    }

    public function getDateTimeTo() 
    {
        return sprintf("%s 23:59:59", $this->report_to);
    }
}
