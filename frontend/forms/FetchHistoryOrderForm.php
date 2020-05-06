<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Order;
use common\models\Game;
use yii\helpers\ArrayHelper;

class FetchHistoryOrderForm extends Order
{
    public $start_date;
    public $end_date;

    /*Attribute for query*/
    public $item_title;

    private $_command;

    public function rules()
    {
        return [
            ['customer_id', 'required'],
            [['game_id', 'status'],'trim'],
            [['start_date', 'end_date'], 'safe']
        ];
    }
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Order::find();
        $command->where(['customer_id' => $this->customer_id]);
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        if ($this->game_id) {
            $command->andWhere(['game_id' => $this->game_id]);
        }
        if ($this->status) {
            if ($this->status != Order::STATUS_PROCESSING) {
                $command->andWhere(['status' => $this->status]);
            } else {
                $command->andWhere(['in', 'status', [Order::STATUS_PROCESSING, Order::STATUS_PARTIAL]]);
            }
        }
        $command->orderBy(['created_at' => SORT_DESC]);
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchGames()
    {
        $games = Game::find()->all();
        $games = ArrayHelper::map($games, 'id', 'title');
        array_unshift($games, 'Choose one game');
        return $games;
    }

    public function fetchStatusList()
    {
        return [
            Order::STATUS_VERIFYING => 'Verifying',
            Order::STATUS_PENDING => 'Pending',
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_COMPLETED => 'Completed',
            Order::STATUS_CONFIRMED => 'Confirmed',
            Order::STATUS_DELETED => 'Deleted',
            Order::STATUS_CANCELLED => 'Cancelled',
        ];
    }
}
