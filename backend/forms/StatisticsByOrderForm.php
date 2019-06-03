<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use dosamigos\chartjs\ChartJs;
use common\components\helpers\FormatConverter;

class StatisticsByOrderForm extends Model
{
    public $start_date;
    public $end_date;
    public  $period;

    public function rules()
    {
        return [
            ['start_date', 'default', 'value' => date('Y-m-01')],
            ['end_date', 'default', 'value' => date('Y-m-t')],
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
        $total_prices = array_combine($labels, $total_prices);

        $game_packs = array_map(function($model) { 
          return round($model['game_pack'], 1);
        }, $models);
        $game_packs = array_combine($labels, $game_packs);
        
        $datasets = [
            [
                'label' => "Số gói",
                'backgroundColor' => "rgba(54,198,211,0.2)",
                'borderColor' => "rgba(54,198,211,1)",
                'pointBackgroundColor' => "rgba(54,198,211,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(54,198,211,1)",
                'data' => array_values($game_packs)
            ],
            [
                'label' => "Số Kcoin",
                'backgroundColor' => "rgba(255,99,132,0.2)",
                'borderColor' => "rgba(255,99,132,1)",
                'pointBackgroundColor' => "rgba(255,99,132,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(255,99,132,1)",
                'data' => array_values($total_prices)
            ],
        ];
        return ChartJs::widget([
            'type' => 'bar',
            'options' => [
                'height' => 200,
                'width' => 400
            ],
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets
            ]
        ]);
    }
    
    protected function createCommand()
    {
        $select = ["SUM(game_pack) as game_pack", "SUM(total_price) as total_price", "YEAR(created_at) as `year`", "QUARTER(created_at) as `quarter`", "MONTH(created_at) as `month`", "WEEK(created_at) as `week`", "DAY(created_at) as `day`"];
        $command = Order::find();
        $command->select($select);
        $command->where(["IN", "status", [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_COMPLETED]]);

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
