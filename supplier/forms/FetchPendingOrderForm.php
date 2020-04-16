<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use supplier\models\Order;
use supplier\models\OrderSupplier;
use supplier\models\User;
use supplier\models\Game;

class FetchPendingOrderForm extends FetchOrderForm
{
    public $order_id;
    public $game_id;
    public $request_start_date;
    public $request_end_date;
    public $supplier_id;
    public $status;

    public function rules()
    {
        return [
            [['supplier_id'], 'required'],
            [['order_id', 'game_id', 'request_start_date', 'request_end_date'], 'trim'],
            ['status', 'safe'],
        ];
    }

    protected $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $table = OrderSupplier::tableName();
        $orderTable = Order::tableName();
        $command = OrderSupplier::find();
        $command->innerJoin($orderTable, "$orderTable.id = $table.order_id");
        $command->select(["$table.*"]);
        
        if ($this->supplier_id) {
            $command->andWhere(["$table.supplier_id" => $this->supplier_id]);
        }

        if ($this->game_id) {
            $command->andWhere(["$table.game_id" => $this->game_id]);
        }
        if ($this->request_start_date) {
            $command->andWhere(['>=', "$table.requested_at", $this->request_start_date]);
        }
        if ($this->request_end_date) {
            $command->andWhere(['<=', "$table.requested_at", $this->request_end_date]);
        }
        $command->andWhere(["$table.status" => OrderSupplier::STATUS_APPROVE]);
        if ($this->status) {
            if ($this->status != OrderSupplier::STATUS_APPROVE) {
                $command->andWhere(["{$orderTable}.state" => $this->status]);
            } else {
                $command->andWhere(["{$orderTable}.state" => null]);
            }
        }

        $command->with('order');
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
