<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\PaymentTransaction;
use dosamigos\chartjs\ChartJs;

class StatisticsByTransactionForm extends Model
{
    public $start_date;
    public $end_date;
    public $period;

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
        $models = $command->orderBy(['year' => SORT_ASC, 'month' => SORT_ASC, 'day' => SORT_ASC])->asArray()->all();
        $total_prices = array_map(function($model) { 
          return round($model['total_price'], 1);
        }, $models);
        $labels = array_map(function($model) { 
          return sprintf("%s-%s-%s", $model['year'], str_pad($model['month'], 2, "0", STR_PAD_LEFT)  , str_pad($model['day'], 2, "0", STR_PAD_LEFT));
        }, $models);
        $datasets = [
            [
                'label' => "Doanh thu",
                'backgroundColor' => "rgba(54,198,211,0.2)",
                'borderColor' => "rgba(54,198,211,1)",
                'pointBackgroundColor' => "rgba(54,198,211,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(54,198,211,1)",
                'data' => array_values($total_prices)
            ],
            
        ];
        return ChartJs::widget([
            'type' => 'line',
            'options' => [
                'height' => 400,
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
        $command = PaymentTransaction::find();
        $command->select(["SUM(total_price) as total_price", "YEAR(payment_at) as `year`", "MONTH(payment_at) as `month`", "DAY(payment_at) as `day`"]);
        $command->where(["status" => PaymentTransaction::STATUS_COMPLETED]);

        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }

        if ($this->period) {
            switch ($this->period) {
                case 'year': 
                    $command->groupBy(['year']);
                    break;
                case 'quarter':
                    break;
                case 'month':
                    $command->groupBy(['year', 'month']);
                    break;
                case 'week': 
                    break;
                default: //day
                    $command->groupBy(['year', 'month', 'day']);
                    # code...
                    break;
            }
        }
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
