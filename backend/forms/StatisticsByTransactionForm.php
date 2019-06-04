<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\PaymentTransaction;
use dosamigos\chartjs\ChartJs;
use common\components\helpers\FormatConverter;

class StatisticsByTransactionForm extends Model
{
    public $start_date;
    public $end_date;
    public $period;

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
            ['period', 'default', 'value' => 'day']
        ];
    }

    private $_command;

    public function showChar()
    {
        $command = $this->getCommand();
        $models = $command->asArray()->all();
        switch ($this->period) {
            case 'week':
                $labels = array_map(function($model) {
                    return sprintf("Tuần %s", $model['week'] + 1);
                }, $models);
                $range = range(date('W', strtotime($this->start_date)), date('W', strtotime($this->end_date)));
                $range = array_map(function($d) {
                    return sprintf("Tuần %s", $d);
                }, $range);
                break;
            case 'month':
                $labels = array_map(function($model) {
                    return sprintf("Tháng %s/%s", str_pad($model['month'], 2, "0", STR_PAD_LEFT), $model['year']);
                }, $models);
                $range = FormatConverter::getDateRange($this->start_date, $this->end_date, 1, 'month');
                $range = array_map(function($d) {
                    return sprintf("Tháng %s/%s", date('m', strtotime($d)), date('Y', strtotime($d)));
                }, $range);
                break;
            case 'quarter':
                $labels = array_map(function($model) {
                    return sprintf("Quý %s/%s", $model['quarter'], $model['year']);
                }, $models);
                $range = FormatConverter::getQuarterRange($this->start_date, $this->end_date);
                $range = array_map(function($d) {
                    $quarter = ceil(date('m', strtotime($d)) / 3);
                    return sprintf("Quý %s/%s", $quarter, date('Y', strtotime($d)));
                }, $range);
                break;
            default:
                $labels = array_map(function($model) {
                  return sprintf("%s-%s-%s", $model['year'], str_pad($model['month'], 2, "0", STR_PAD_LEFT)  , str_pad($model['day'], 2, "0", STR_PAD_LEFT));
                }, $models);
                $range = FormatConverter::getDateRange($this->start_date, $this->end_date);
                break;
        }
        
        $total_prices = array_map(function($model) { 
            return round($model['total_price'], 1);
        }, $models);

        $data = array_combine($labels, $total_prices);
        $rangePeriod = array_fill_keys($range, 0);
        $data = array_merge($rangePeriod, $data);
        
        $datasets = [
            [
                'label' => "Doanh thu",
                'backgroundColor' => "rgba(54,198,211,0.2)",
                'borderColor' => "rgba(54,198,211,1)",
                'pointBackgroundColor' => "rgba(54,198,211,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(54,198,211,1)",
                'data' => array_values($data)
            ],
            
        ];
        return ChartJs::widget([
            'type' => 'line',
            'options' => [
                'height' => 200,
                'width' => 400
            ],
            'data' => [
                'labels' => array_keys($data),
                'datasets' => $datasets
            ]
        ]);
    }
    
    protected function createCommand()
    {
        $select = ["SUM(total_price) as total_price", "YEAR(payment_at) as `year`", "QUARTER(payment_at) as `quarter`", "MONTH(payment_at) as `month`", "WEEK(payment_at) as `week`", "DAY(payment_at) as `day`"];
        $command = PaymentTransaction::find();
        $command->select($select);
        $command->where(["status" => PaymentTransaction::STATUS_COMPLETED]);

        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }

        if ($this->period) {
            switch ($this->period) {
                case 'quarter':
                    $command->groupBy(['year', 'quarter']);
                    break;
                case 'month':
                    $command->groupBy(['year', 'month']);
                    break;
                case 'week': 
                    $command->groupBy(['year', 'week']);
                    break;
                default: //day
                    $command->groupBy(['year', 'month', 'day']);
                    break;
            }
        }
        $command->orderBy(['year' => SORT_ASC, 'quarter' => SORT_ASC, 'month' => SORT_ASC, 'week' => SORT_ASC, 'day' => SORT_ASC]);
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }
}
