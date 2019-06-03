<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use common\models\User;
use common\models\Game;

class FetchOrderForm extends Model
{
    public $q;
    public $customer_id;
    public $game_id;
    public $start_date;
    public $end_date;
    public $saler_id;
    public $handler_id;
    public $provider_id;
    public $status;
    public $agency_id;
    public $reseller_id;
    
    public function rules()
    {
        return [
            ['q', 'trim'],
            [['game_id', 'customer_id', 'saler_id', 'handler_id', 'start_date', 'end_date', 'status'], 'safe'],
            ['start_date', 'default', 'value' => date('Y-m-d', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d')],
            [['provider_id', 'agency_id', 'reseller_id'], 'safe'],
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
        $command = Order::find();
        
        if ($this->q) {
            $command->andWhere(['OR',
                ['id' => $this->q],
                ['auth_key' =>  $this->q]
            ]);
            $this->_command = $command;
            return;
        }
        if ($this->customer_id) {
            $command->andWhere(['customer_id' => $this->customer_id]);
        }
        if ($this->game_id) {
            $command->andWhere(['game_id' => $this->game_id]);
        }
        if ($this->saler_id) {
            $command->andWhere(['saler_id' => $this->saler_id]);
        }
        if ($this->handler_id) {
            if ($this->handler_id == -1) {
                $command->andWhere(['handler_id' => null]);
                $this->handler_id = '';
            } else {
                $command->andWhere(['handler_id' => $this->handler_id]);
            }
        }
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        if ($this->status) {
            if (is_array($this->status)) {
                $command->andWhere(['IN', 'status', $this->status]);
            } else {
                $command->andWhere(['status' => $this->status]);
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

    public function getCustomer()
    {
        if ($this->customer_id) {
            return User::findOne($this->customer_id);
        }
    }

    public function getSaler()
    {
        if ($this->saler_id) {
            return User::findOne($this->saler_id);
        }
    }

    public function getHandler()
    {
        if ($this->handler_id) {
            return User::findOne($this->handler_id);
        }
    }

    public function getGame()
    {
        if ($this->game_id) {
            return Game::findOne($this->game_id);
        }
    }

    public function getStatus()
    {
        $list = Order::getStatusList();
        return $list;
    }

    public function fetchGames()
    {
        return ArrayHelper::map(Game::find()->all(), 'id', 'title');
    }
}
