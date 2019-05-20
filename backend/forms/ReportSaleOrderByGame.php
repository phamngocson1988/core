<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\Game;

class ReportSaleOrderByGame extends Model
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
}
