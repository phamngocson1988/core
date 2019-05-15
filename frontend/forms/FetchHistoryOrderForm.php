<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Order;
use common\models\Game;
use yii\helpers\ArrayHelper;

class FetchHistoryOrderForm extends Order
{
    public $user_id;
    public $start_date;
    public $end_date;
    public $game_id;

    /*Attribute for query*/
    public $item_title;

    private $_command;

    public function rules()
    {
        return [
            ['user_id', 'required'],
            ['start_date', 'default', 'value' => date('Y-m-01')],
            ['end_date', 'default', 'value' => date('Y-m-d')],
            ['game_id','trim']
        ];
    }
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = self::find();
        $command->joinWith('items');
        $command->select(['order.*', 'order_items.item_title']);
        $command->where(['customer_id' => $this->user_id]);
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        if ($this->game_id) {
            $command->andWhere(['order_items.game_id' => $this->game_id]);
        }
        $command->orderBy(['order.created_at' => SORT_DESC]);
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
}
