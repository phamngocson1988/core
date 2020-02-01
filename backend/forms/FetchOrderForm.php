<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use common\models\User;
use common\models\Game;
use backend\models\Supplier;

class FetchOrderForm extends Model
{
    public $q;
    public $customer_id;
    public $game_id;
    public $start_date;
    public $end_date;
    public $saler_id;
    public $orderteam_id;
    public $supplier_id;
    public $status;
    public $agency_id;
    public $is_reseller;
    public $request_cancel;
    public $customer_phone;
    public $completed_from;
    public $completed_to;
    public function rules()
    {
        return [
            [['q', 'customer_phone'], 'trim'],
            [['game_id', 'customer_id', 'saler_id', 'orderteam_id', 'status'], 'safe'],
            [['start_date', 'end_date'], 'safe'],
            [['completed_from', 'completed_to'], 'safe'],
            [['supplier_id', 'agency_id', 'is_reseller'], 'safe'],
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
        if ($this->customer_id) {
            $command->andWhere(["$table.customer_id" => $this->customer_id]);
        }
        if ($this->customer_phone) {
            $command->andWhere(["LIKE", "$table.customer_phone", $this->customer_phone]);
        }
        if ($this->game_id) {
            $command->andWhere(["$table.game_id" => $this->game_id]);
        }
        if ($this->saler_id) {
            $command->andWhere(["$table.saler_id" => $this->saler_id]);
        }
        if ($this->supplier_id) {
            $command->andWhere(["$table.supplier_id" => $this->supplier_id]);
        }
        if ($this->request_cancel) {
            $command->andWhere(["$table.request_cancel" => $this->request_cancel]);
        }
        if ($this->orderteam_id) {
            if ($this->orderteam_id == '-1') {
                $command->andWhere(["IS", "$table.orderteam_id", null]);
            } else {
                $command->andWhere(["$table.orderteam_id" => $this->orderteam_id]);
            }
        }
        if ($this->start_date) {
            $command->andWhere(['>=', "$table.created_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$table.created_at", $this->end_date]);
        }

        if ($this->completed_from) {
            $command->andWhere(['>=', "$table.completed_at", $this->completed_from]);
        }
        if ($this->completed_to) {
            $command->andWhere(['<=', "$table.completed_at", $this->completed_to]);
        }

        if ($this->status) {
            if (is_array($this->status)) {
                $command->andWhere(['IN', "$table.status", $this->status]);
            } else {
                $command->andWhere(["$table.status" => $this->status]);
            }
        }
        if ($this->is_reseller) {
            $userTable = User::tableName();
            $command->leftJoin($userTable, "$table.customer_id = $userTable.id")->andWhere(["$userTable.is_reseller" => $this->is_reseller]);
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

    public function getOrderteam()
    {
        if ($this->orderteam_id) {
            return User::findOne($this->orderteam_id);
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

    public function fetchSuppliers()
    {
        $suppliers = Supplier::find()->all();
        $mapping = [];
        foreach ($suppliers as $supplier) {
            $user = $supplier->user;
            $mapping[$user->id] = sprintf("%s (%s)", $user->name, $user->email);
        }
        return $mapping;
    }
}
