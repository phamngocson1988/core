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

    private $_command;

    public function rules()
    {
        return [
            ['game_id', 'trim'],
            ['start_date', 'default', 'value' => date('Y-m-d', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d')],
        ];
    }

    public function fetch()
    {
        // Find all game in period
        $status = $this->availabelStatus();
        $command = $this->getCommand();
        $command->select(['id', 'game_id', 'game_title', 'SUM(game_pack) as game_pack']);
        $command->groupBy('game_id');
        $command->andWhere(['IN', 'status', $status]);
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
}
