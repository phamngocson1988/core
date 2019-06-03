<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\Game;

class ReportProcessOrderByGame extends Model
{
    public $game_id;
    public $start_date;
    public $end_date;
    public $limit = '5';

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
            ['start_date', 'default', 'value' => date('Y-m-d', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d')],
            ['limit', 'default', 'value' => '5'],
            ['game_id', 'trim'],
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
        $status = $this->availabelStatus();
        $command = $this->getCommand();
        $command->select(['id', 'game_id', 'game_title', 'SUM(game_pack) as game_pack']);
        $command->groupBy('game_id');
        $command->andWhere(['IN', 'status', $status]);
        $command->orderBy(['game_pack' => SORT_DESC]);
        $command->offset(0);
        $command->limit($this->limit);
        $games = $command->indexBy('game_id')->asArray()->all();

        // query report
        foreach ($games as $id => $game) {
            // pending order
            $penddingCommand = $this->getCommand();
            $penddingCommand->andWhere(['IN', 'status', $this->unCompleteStatus()]);
            $penddingCommand->andWhere(['game_id' => $id]);
            $penddingCount = $penddingCommand->count();

            // completed order
            $completedCommand = $this->getCommand();
            $completedCommand->andWhere(['IN', 'status', $this->completeStatus()]);
            $completedCommand->andWhere(['game_id' => $id]);
            $completedCount = $completedCommand->count();

            $rate = $completedCount / ($completedCount + $penddingCount) * 100;
            $avarageTime = $completedCommand->sum('process_duration_time') / ($completedCount * 60); //mins

            $games[$id]['completed_rate'] = $rate;
            $games[$id]['avarage_time'] = $avarageTime;
        }
        // Other games
        $otherCommand = $this->getCommand();
        $otherCommand->select(['status', 'SUM(game_pack) as game_pack, sum(process_duration_time) as process_duration_time']);
        $otherCommand->groupBy('status');
        $otherCommand->andWhere(['NOT IN', 'game_id', array_keys($games)]);
        if ($otherCommand->count()) {
            $otherGames = $otherCommand->asArray()->all();
            $completeStatus = $this->completeStatus();
            $completeOrder = array_filter($otherGames, function ($element) use ($completeStatus) { return in_array($element['status'], $completeStatus); } ); 
            $completedCount = array_sum(array_column($completeOrder, 'game_pack'));
            $completedTime = array_sum(array_column($completeOrder, 'process_duration_time'));
            $totalCount = array_sum(array_column($otherGames, 'game_pack'));
            $rate = $completedCount / ($totalCount) * 100;
            $avarageTime = $completedTime / ($completedCount * 60); //mins
            $other = [
                'id' => 'other',
                'game_id' => 'other_game',
                'game_title' => 'Game khác',
                'game_pack' => $totalCount,
                'completed_rate' => $rate,
                'avarage_time' => $avarageTime,
            ];
            $games['other'] = $other;
        }
        
        return $games;
    }

    public function createCommand()
    {
        $command = Order::find();
        $command->where("1=1");
        if ($this->game_id) {
            $command->andWhere(['game_id' => $this->game_id]);
        }
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
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

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->game_id);
        }
        return $this->_game;
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

    public function unCompleteStatus()
    {
        return [
            Order::STATUS_VERIFYING,
            Order::STATUS_PENDING, 
        ];
    }

    public function completeStatus()
    {
        return [
            Order::STATUS_PROCESSING, 
            Order::STATUS_COMPLETED
        ];
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
