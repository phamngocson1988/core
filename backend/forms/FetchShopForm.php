<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\User;
use backend\models\Supplier;
use backend\models\Game;
use backend\models\OrderSupplier;

class FetchShopForm extends Model
{
	public $id;
    public $customer_id;
    public $saler_id;
    public $supplier_id;
    public $orderteam_id;
    public $payment_method;
    public $game_id;
    public $start_date;
    public $end_date;
    public $status;

    protected $_command;
    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    protected function createCommand()
    {

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

    public function fetchPaymentMethods()
    {
        $paygates = \backend\models\Paygate::find()->all();
        $list = ArrayHelper::map($paygates, 'identifier', 'name');
        $list['kinggems'] = 'King Coin';
        return $list;
    }

    public function getStatus()
    {
        $list = Order::getStatusList();
        return $list;
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
