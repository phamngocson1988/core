<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\OrderSupplier;
use backend\models\Game;
use dosamigos\chartjs\ChartJs;

class ReportGameProfitForm extends Model
{
    public $game_ids;
    public $confirmed_from;
    public $confirmed_to;
    public $period;
    public $limit;

    private $_command;

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }

    protected function createCommand()
    {
        $orderTable = Order::tableName();
        $orderSupplierTable = OrderSupplier::tableName();
        $command = OrderSupplier::find();
        $command->innerJoin($orderTable, "{$orderTable}.id = {$orderSupplierTable}.order_id");
        $command->select([
            "{$orderSupplierTable}.game_id", 
            "SUM({$orderSupplierTable}.total_price) as sum_fee",
            "SUM({$orderSupplierTable}.doing) as sum_doing",
            "SUM({$orderTable}.total_price * {$orderTable}.rate_usd * {$orderSupplierTable}.doing / {$orderSupplierTable}.quantity) as sum_profit",
            "SUM(({$orderTable}.total_price * {$orderTable}.rate_usd * {$orderSupplierTable}.doing / {$orderSupplierTable}.quantity) - {$orderSupplierTable}.total_price) as sum_revenue",
        ]);
        $command->groupBy(["{$orderSupplierTable}.game_id"]);
        $command->orderBy(["sum_profit" => SORT_DESC]);
        $command->andWhere(["{$orderSupplierTable}.status" => OrderSupplier::STATUS_CONFIRMED]);
        $command->with('game');
        if ($this->confirmed_from) {
            $command->andWhere(['>=', "{$orderTable}.confirmed_at", $this->confirmed_from]);
        }
        if ($this->confirmed_to) {
            $command->andWhere(['<=', "{$orderTable}.confirmed_at", $this->confirmed_to]);
        }

        if ($this->game_ids) {
            $command->andWhere(["IN", "{$orderSupplierTable}.game_id", $this->game_ids]);
        }

        if ($this->limit) {
            $command->limit($this->limit);
        }

        $this->_command = $command;
        return $command;
    }

    public function getStatistic()
    {
        $command = $this->getCommand();
        // die($command->createCommand()->getRawSql());
        return $command->asArray()->all();
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

    public function fetchGames()
    {
        return ArrayHelper::map(Game::find()->all(), 'id', 'title');
    }
}
