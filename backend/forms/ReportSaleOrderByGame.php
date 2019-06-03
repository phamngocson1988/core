<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\Game;
use dosamigos\chartjs\ChartJs;

class ReportSaleOrderByGame extends Model
{
    public $game_id;
    public $start_date;
    public $end_date;
    public $period;
    public $limit;

    protected $_game;
    private $_command;

    public function init()
    {
        if ($this->limit === null) $this->limit = '5';
        if ($this->limit != '0') $this->game_id = null;
    }

    public function rules()
    {
        return [
            ['game_id', 'trim'],
            ['start_date', 'default', 'value' => date('Y-m-d', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d')],
            ['period', 'default', 'value' => 'day'],
            ['limit', 'default', 'value' => '5'],
            ['game_id', 'required', 'when' => function($model) {
                return $model->limit == 0;
            }, 'whenClient' => "function (attribute, value) {
                return $('#limit').val() == '0';
            }",
            'message' => 'Chọn một game để thống kê'],
        ];
    }

    public function fetch()
    {
        if (!$this->validate()) return false;
        // Find all game in period
        $command = $this->getCommand();
        if ($this->game_id) {
            return $command->asArray()->all();
        }

        $command->offset(0);
        $command->limit($this->limit);
        $games = $command->asArray()->all();
       
        // Other games
        $otherCommand = $this->getCommand();
        $otherCommand->andWhere(['NOT IN', 'game_id', array_column($games, 'game_id')]);
        if ($otherCommand->count()) {
            $other = [
                'id' => 'other',
                'game_id' => 'other_game',
                'game_title' => 'Game khác',
                'game_pack' => $otherCommand->sum('game_pack'),
                'total_price' => $otherCommand->sum('total_price'),
            ];
            $games[] = $other;
        }
        
        return $games;
    }

    public function showChar()
    {
        $models = $this->fetch();
        $game_packs = array_map(function($model) { 
          return round($model['game_pack'], 1);
        }, $models);
        $total_prices = array_map(function($model) { 
            return round($model['total_price'], 1);
          }, $models);
        $labels = array_column($models, 'game_title');
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

    public function createCommand()
    {
        $command = Order::find();
        $command->select(['id', 'game_id', 'game_title', 'SUM(game_pack) as game_pack', 'SUM(total_price) as total_price']);
        $command->where(['IN', 'status', $this->completeStatus()]);
        if ($this->game_id) {
            $command->andWhere(['game_id' => $this->game_id]);
        }
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        $command->groupBy('game_id');
        $command->orderBy(['game_pack' => SORT_DESC]);
        return $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return clone $this->_command;
    }


    public function fetchGames()
    {
        return ArrayHelper::map(Game::find()->all(), 'id', 'title');
    }

    public function completeStatus()
    {
        return [
            Order::STATUS_PROCESSING, 
            Order::STATUS_COMPLETED
        ];
    }

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->game_id);
        }
        return $this->_game;
    }

    public function getLimitOptions()
    {
        return [
            '3' => 'Top 3',
            '5' => 'Top 5',
            '10' => 'Top 10',
            '0' => 'Game cụ thể',
        ];
    }
}
