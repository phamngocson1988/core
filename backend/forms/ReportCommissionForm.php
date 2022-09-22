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

class ReportCommissionForm extends Model
{
    public $user_id;
    public $start_date;
    public $end_date;
    public $role;

    private $_command;
    
    public function rules()
    {
        return [
            ['role', 'required'],
            ['role', 'in', 'range' => ['saler', 'orderteam']]
        ];
    }

    public function fetch()
    {
        if (!$this->validate()) return [];
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $column = $this->getStaffColumn();
        $command = Order::find()
        ->where(['status' => Order::STATUS_CONFIRMED])
        ->andWhere(['>', 'real_profit', 'expected_profit']);
        
        if ($this->user_id) {
            $command->andWhere([$column => $this->user_id]);
        }
        if ($this->start_date) {
            $command->andWhere(['>=', "confirmed_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "confirmed_at", $this->end_date]);
        }
        $command->groupBy($column);

        $command->select([
            "$column as user_id",
            "SUM(saler_order_commission) as saler_order_commission", 
            "SUM(saler_sellout_commission) as saler_sellout_commission", 
            "SUM(orderteam_order_commission) as orderteam_order_commission", 
            "SUM(orderteam_sellout_commission) as orderteam_sellout_commission", 
        ]);
        $command->asArray();
        $this->_command = $command;
    }

    protected function getStaffColumn() 
    {
        $roleMapping = ['saler' => 'saler_id', 'orderteam' => 'orderteam_id'];
        return ArrayHelper::getValue($roleMapping, $this->role, '');
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchOrderTeams()
    {
        $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
        $orderTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('orderteam_manager');
        $adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
        $orderTeamIds = array_merge($orderTeamIds, $orderTeamManagerIds, $adminTeamIds);
        $orderTeamIds = array_unique($orderTeamIds);
        $orderTeamObjects = User::findAll($orderTeamIds);
        return ArrayHelper::map($orderTeamObjects, 'id', 'email');   
    }

    public function fetchSalerTeam()
    {
        $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
        $salerTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('saler_manager');
        $adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
        $salerTeamIds = array_merge($salerTeamIds, $salerTeamManagerIds, $adminTeamIds);
        $salerTeamIds = array_unique($salerTeamIds);
        $salerTeamObjects = User::findAll($salerTeamIds);
        return ArrayHelper::map($salerTeamObjects, 'id', 'email');
    }
}
