<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use supplier\models\Order;
use supplier\models\User;
use supplier\models\Game;

class FetchOrderForm extends Model
{
    public $q;
    public $customer_id;
    public $game_id;
    public $start_date;
    public $end_date;
    public $status;
    public $request_cancel;
    public $customer_phone;
    public $supplier_id;
    public $supplier_accept;

    public function rules()
    {
        return [
            [['supplier_id', 'supplier_accept'], 'required'],
            [['q', 'customer_phone'], 'trim'],
            [['game_id', 'customer_id', 'start_date', 'end_date', 'status'], 'safe'],
            [['start_date', 'end_date'], 'safe'],
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
        $table = Order::tableName();
        
        if ($this->q) {
            $command->andWhere(['OR',
                ["$table.id" => $this->q],
                ["$table.auth_key" =>  $this->q]
            ]);
            $this->_command = $command;
            return;
        }
        $command->andWhere(["$table.supplier_id" => $this->supplier_id]);
        $command->andWhere(["$table.supplier_accept" => $this->supplier_accept]);
        if ($this->customer_id) {
            $command->andWhere(["$table.customer_id" => $this->customer_id]);
        }
        if ($this->customer_phone) {
            $command->andWhere(["LIKE", "$table.customer_phone", $this->customer_phone]);
        }
        if ($this->game_id) {
            $command->andWhere(["$table.game_id" => $this->game_id]);
        }
        if ($this->request_cancel) {
            $command->andWhere(["$table.request_cancel" => $this->request_cancel]);
        }
        if ($this->start_date) {
            $command->andWhere(['>=', "$table.created_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$table.created_at", $this->end_date]);
        }
        if ($this->status) {
            if (is_array($this->status)) {
                $command->andWhere(['IN', "$table.status", $this->status]);
            } else {
                $command->andWhere(["$table.status" => $this->status]);
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
