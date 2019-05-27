<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use dosamigos\chartjs\ChartJs;

class StatisticsByOrderForm extends Model
{
    public $start_date;
    public $end_date;

    public function rules()
    {
        return [
            ['start_date', 'default', 'value' => date('Y-m-01')],
            ['end_date', 'default', 'value' => date('Y-m-t')],
        ];
    }

    private $_command;

    public function showChar()
    {
        $command = $this->getCommand();
        $models = $command->asArray()->all();
        $game_packs = array_map(function($model) { 
          return round($model['game_pack'], 1);
        }, $models);
        $total_prices = array_map(function($model) { 
            return round($model['total_price'], 1);
          }, $models);
        $labels = array_column($models, 'date');
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
        $select = ["SUM(game_pack) as game_pack", "SUM(total_price) as total_price", "DATE(created_at) as `date`"];
        $command = Order::find();
        $command->select($select);
        $command->where(["IN", "status", [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_COMPLETED]]);

        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        $command->groupBy(['date']);
        $command->orderBy(['date' => SORT_ASC]);
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
