<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;
use common\models\OrderItems;
use common\models\User;
use common\models\Game;

class ReportByGameForm extends Model
{
    public $game_id;
    public $start_date;
    public $end_date;

    public function rules()
    {
        return [
            [['game_id'], 'safe'],
            ['start_date', 'default', 'value' => date('Y-m-d', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d')],
        ];
    }

    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = OrderItems::find();
        $command->joinWith('order');
        $command->select(['order_items.id', 'order_items.game_id', 'order_items.total', 'order_items.quantity']);
        $command->where(["IN", "order.status", [Order::STATUS_PROCESSING, Order::STATUS_COMPLETED]]);
        $command->andWhere(['order_items.type' => OrderItems::TYPE_PRODUCT]);

        $this->_command = $command;
        if ($this->start_date) {
            $command->andWhere(['>=', 'order.created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'order.created_at', $this->end_date . " 23:59:59"]);
        }
        $command->groupBy('order_items.game_id');
        $command->with('game');
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function getGame()
    {
        if ($this->game_id) {
            return Game::findOne($this->game_id);
        }
    }
}
