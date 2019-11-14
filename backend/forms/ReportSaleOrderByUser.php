<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;

class ReportSaleOrderByUser extends Model
{
    public $start_date;
    public $end_date;
    public $period;

    protected $_game;
    private $_command;
    protected $filter_column = "filter";

    public function init()
    {
        if (!$this->start_date) $this->start_date = date('Y-m-d 00:00', strtotime('-29 days'));
        if (!$this->end_date) $this->end_date = date('Y-m-d 23:59');
    }

    public function rules()
    {
        return [
            ['start_date', 'default', 'value' => date('Y-m-d 00:00', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d 23:59')],
            [['start_date', 'end_date'], 'required'],
            ['period', 'default', 'value' => 'day'],
        ];
    }

    public function fetch()
    {
        if (!$this->validate()) return false;
        $command = $this->getCommand();
        $command->select(array_merge(['id', 'saler_id', 'SUM(quantity) as quantity', 'SUM(total_price) as total_price'], [$this->getSelectByPeriod()]));
        $command->with('saler');
        $command->orderBy(['created_at' => SORT_ASC]);
        $command->groupBy([$this->getGroupByPeriod(), 'saler_id']);
        $reports = $command->asArray()->all();
        $filterColumn = $this->filter_column;
        $reportDates = array_unique(array_column($reports, $filterColumn));
        $salerIds = array_column($reports, 'saler_id');
        $salerIds = array_filter($salerIds);
        $salerIds = array_unique($salerIds);
        $orders = [];
        foreach ($reportDates as $date) {
            $reportByDates = array_filter($reports, function($r) use ($date, $filterColumn) {
                return $r[$filterColumn] == $date;
            });
            foreach ($salerIds as $salerId) {
                $reportBySaler = array_filter($reportByDates, function($r) use ($salerId) {
                    return $r['saler_id'] == $salerId;
                });
                if (!$reportBySaler) continue;
                $salerInfo = reset($reportBySaler);
                $totalPackage = array_sum(array_column($reportBySaler, 'quantity'));
                $totalPrice = array_sum(array_column($reportBySaler, 'total_price'));
                $orders[$date][$salerId]['saler_id'] = $salerId;
                $orders[$date][$salerId]['name'] = $salerInfo['saler']['name'];
                $orders[$date][$salerId]['quantity'] = $totalPackage;
                $orders[$date][$salerId]['total_price'] = $totalPrice;
            }
        }
        return $orders;
    }

    public function createCommand()
    {
        $command = Order::find();
        $command->where(['BETWEEN', 'created_at', $this->start_date, $this->end_date]);
        $command->andWhere(['IN', 'status', $this->availabelStatus()]);
        $command->andWhere(['IS NOT', 'saler_id', null]);
        return $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return clone $this->_command;
    }

    public function getGroupByPeriod()
    {
        switch ($this->period) {
            case 'quarter':
                $group = "CONCAT_WS('-', YEAR(created_at), QUARTER(created_at))";
                break;
            case 'month':
                $group = "CONCAT_WS('-', YEAR(created_at), MONTH(created_at))";
                break;
            case 'week': 
                $group = "CONCAT_WS('-', YEAR(created_at), WEEK(created_at))";
                break;
            default: //day
                $group = "CONCAT_WS('-', YEAR(created_at), MONTH(created_at), DAY(created_at))";
                break;
        }
        return $group;
    }

    public function getLabelByPeriod($label)
    {
        switch ($this->period) {
            case 'quarter':
                list($year, $quarter) = explode("-", $label);
                return sprintf("Qúy %s / %s", $quarter, $year);
            case 'month':
                list($year, $month) = explode("-", $label);
                return sprintf("Tháng %s / %s", str_pad($month, 2, "0", STR_PAD_LEFT), $year);
            case 'week': 
                list($year, $week) = explode("-", $label);
                return sprintf("Tuần %s / %s", $week + 1, $year);
            default: //day
                list($year, $month, $day) = explode("-", $label);
                return sprintf("%s-%s-%s", $year, str_pad($month, 2, "0", STR_PAD_LEFT), str_pad($day, 2, "0", STR_PAD_LEFT));
        }
        return $group;
    }

    public function getSelectByPeriod()
    {
        return $this->getGroupByPeriod() . " AS " . $this->filter_column;
    }

    public function availabelStatus()
    {
        return [
            Order::STATUS_VERIFYING,
            Order::STATUS_PENDING, 
            Order::STATUS_PROCESSING, 
            Order::STATUS_COMPLETED
        ];
    }
}
