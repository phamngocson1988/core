<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\User;
use backend\models\Game;
use backend\models\Supplier;
use backend\models\OrderSupplier;

class FetchPendingOrderForm extends Model
{
    public $id;
    public $game_id;
    public $start_date;
    public $end_date;
    public $supplier_id;
    public $orderteam_id;
    public $saler_id;
    public $status;

    private $_command;
    
    protected function createCommand()
    {
        $table = Order::tableName();
        $supplierTable = OrderSupplier::tableName();

        $command = Order::find();
        $orderSupplierStatus = sprintf("'%s', '%s', '%s'", OrderSupplier::STATUS_REQUEST, OrderSupplier::STATUS_APPROVE, OrderSupplier::STATUS_PROCESSING);
        $command->leftJoin($supplierTable, "$table.id = $supplierTable.order_id AND $supplierTable.status IN ($orderSupplierStatus)");
        $command->select(["$table.*"]);
        
        if ($this->id) {
            $command->andWhere(["$table.id" => $this->id]);
        }
        if ($this->supplier_id) {
            $command->andWhere(["$supplierTable.supplier_id" => $this->supplier_id]);
        }
        if ($this->saler_id) {
            $command->andWhere(["$table.saler_id" => $this->saler_id]);
        }
        if ($this->game_id) {
            $command->andWhere(["$table.game_id" => $this->game_id]);
        }
        if ($this->orderteam_id) {
            $command->andWhere(["$table.orderteam_id" => $this->orderteam_id]);
        }
        if ($this->saler_id) {
            $command->andWhere(["$table.saler_id" => $this->saler_id]);
        }
        if ($this->start_date) {
            $command->andWhere(['>=', "$table.created_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$table.created_at", $this->end_date]);
        }

        $command->andWhere(["$table.status" => Order::STATUS_PENDING]);
        if ($this->status) {
            if ($this->status != Order::STATUS_PENDING) {
                $command->andWhere(["{$table}.state" => $this->status]);
            } else {
                $command->andWhere(["{$table}.state" => null]);
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

    public function fetchGames()
    {
        return ArrayHelper::map(Game::find()->all(), 'id', 'title');
    }

    public function fetchSuppliers()
    {
        $userTable = User::tableName();
        $supplierTable = Supplier::tableName();

        $users = User::find()->innerJoin($supplierTable, "$userTable.id = $supplierTable.user_id")->select(["$userTable.id", "$userTable.name"])->orderBy(["$userTable.name" => SORT_ASC])->all();

        return ArrayHelper::map($users, 'id', 'name');
    }

    public function fetchOrderTeams()
    {
        $adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
        // order team
        $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
        $orderTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('orderteam_manager');
        $orderTeamIds = array_merge($orderTeamIds, $orderTeamManagerIds, $adminTeamIds);
        $orderTeamIds = array_unique($orderTeamIds);
        $orderTeamObjects = User::find($orderTeamIds)->where(['IN', 'id', $orderTeamIds])->select(['id', 'name'])->orderBy(["name" => SORT_ASC])->all();
        return ArrayHelper::map($orderTeamObjects, 'id', 'name');
    }

    public function fetchSalerTeams()
    {
        $adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
        // order team
        $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
        $orderTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('sale_manager');
        $orderTeamIds = array_merge($orderTeamIds, $orderTeamManagerIds, $adminTeamIds);
        $orderTeamIds = array_unique($orderTeamIds);
        $orderTeamObjects = User::find($orderTeamIds)->where(['IN', 'id', $orderTeamIds])->select(['id', 'name'])->orderBy(["name" => SORT_ASC])->all();
        return ArrayHelper::map($orderTeamObjects, 'id', 'name');
    }
}
