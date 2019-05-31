<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use backend\models\Game;
use backend\models\Order;
use yii\helpers\ArrayHelper;

/**
 * FetchCustomerForm
 */
class FetchCustomerForm extends User
{
    public $created_start;
    public $created_end;
    public $birthday_start;
    public $birthday_end;
    // public $country_code;
    public $game_id;
    public $purchase_start;
    public $purchase_end;
    public $saler_id;
    // public $is_reseller;
    public $last_purchase_start;
    public $last_purchase_end;
    public $total_purchase_start;
    public $total_purchase_end;
    public $total_topup_start;
    public $total_topup_end;

    public $last_order_date;
    public $total_purchase;
    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $userTable = User::tableName();
        $orderTable = Order::tableName();
        $orderStatus = [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_COMPLETED];
        $command = self::find();
        $command->select(["{$userTable}.*", "{$orderTable}.created_at as last_order_date", "SUM({$orderTable}.total_price) as total_purchase"]);
        $command->leftJoin($orderTable, "{$orderTable}.customer_id = {$userTable}.id");

        if ($this->created_start) {
            $command->andWhere(['>=', "{$userTable}.created_at", $this->created_start . " 00:00:00"]);
        }
        if ($this->created_end) {
            $command->andWhere(['<=', "{$userTable}.created_at", $this->created_end . " 23:59:59"]);
        }

        if ($this->birthday_start) {
            $command->andWhere(['>=', "{$userTable}.birthday", $this->birthday_start . " 00:00:00"]);
        }
        if ($this->birthday_end) {
            $command->andWhere(['<=', "{$userTable}.birthday", $this->birthday_end . " 23:59:59"]);
        }

        if ($this->purchase_start) {
            $command->andWhere(['>=', "{$orderTable}.created_at", $this->purchase_start . " 00:00:00"]);
        }
        if ($this->purchase_end) {
            $command->andWhere(['<=', "{$orderTable}.created_at", $this->purchase_end . " 23:59:59"]);
        }

        

        if ($this->country_code) {
            $command->andWhere(["{$userTable}.country_code" => $this->country_code]);
        }

        if ($this->game_id) {
            $command->andWhere(["{$orderTable}.game_id" => $this->game_id]);
        }

        if ($this->saler_id) {
            $command->andWhere(["{$orderTable}.saler_id" => $this->saler_id]);
        }

        if ($this->is_reseller) {
            $command->andWhere(["{$userTable}.is_reseller" => $this->is_reseller]);
        }

        // Having
        if ($this->total_purchase_start) {
            $command->andHaving(['>=', 'total_purchase', $this->total_purchase_start]);
        }

        if ($this->total_purchase_end) {
            $command->andHaving(['<=', 'total_purchase', $this->total_purchase_end]);
        }

        $command->groupBy("{$userTable}.id");
        $orderBy = ["{$userTable}.id" => "ASC", "{$orderTable}.id" => "ASC"];
        $command->orderBy($orderBy);
        // echo $command->createCommand()->getRawSql();die;
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
        return ArrayHelper::map(Game::find()->all(), 'id', 'title');
    }

    public function fetchSalers()
    {
        $member = Yii::$app->authManager->getUserIdsByRole('saler');
        $manager = Yii::$app->authManager->getUserIdsByRole('sale_manager');
        $admin = Yii::$app->authManager->getUserIdsByRole('admin');

        $salerTeamIds = array_merge($member, $manager, $admin);
        $salerTeamIds = array_unique($salerTeamIds);
        $salerTeamObjects = User::findAll($salerTeamIds);
        $salerTeam = ArrayHelper::map($salerTeamObjects, 'id', 'email');
        return $salerTeam;
    }
}
