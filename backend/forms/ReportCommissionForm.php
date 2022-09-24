<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\OrderCommission;
use backend\models\User;

class ReportCommissionForm extends Model
{
    public $user_ids = [];
    public $start_date;
    public $end_date;

    private $_command;
    /**
     * Array: [["user_id", "username", "commission_type", "role", "user_commission"], ...]
     */
    protected $reportData = [];

    public function init()
    {
        if (!$this->start_date) $this->start_date = date('Y-m-01');
        if (!$this->end_date) $this->end_date = date('Y-m-d');
    }
    
    public function run()
    {
        $command = $this->getCommand();
        $this->reportData = $command->all();
    }

    protected function createCommand()
    {
        $command = OrderCommission::find();
        
        if (count($this->user_ids)) {
            $command->andWhere(['user_id' => $this->user_ids]);
        }
        $command->andWhere(['between', "created_at", $this->start_date . " 00:00:00",  $this->end_date . " 23:59:59"]);
        $command->groupBy(['user_id', 'commission_type']);

        $command->select([
            "user_id",
            "username",
            "order_id",
            "commission_type", 
            "role",
            "SUM(user_commission) as user_commission", 
        ]);
        $command->asArray();
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchUsers()
    {
        $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
        $orderTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('orderteam_manager');

        $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
        $salerTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('saler_manager');

        $adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');

        $userIds = array_merge($orderTeamIds, $orderTeamManagerIds, $salerTeamIds, $salerTeamManagerIds, $adminTeamIds);
        $userIds = array_unique($userIds);
        $users = User::findAll($userIds);

        return ArrayHelper::map($users, 'id', 'username');   
    }

    public function getData() 
    {
        return (array)$this->reportData;
    }

    /**
     * @return [username => 'abc', 'total' => 10]
     */
    public function getCommissionByUser()
    {
        $groups = ArrayHelper::index($this->getData(), null, 'username');
        $result = [];
        foreach (array_keys($groups) as $key) {
            $rows = $groups[$key];
            $commissionRows = array_filter($rows, function($row){
                return $row['commission_type'] === OrderCommission::COMMSSION_TYPE_ORDER;
            });
            $selloutRows = array_filter($rows, function($row){
                return $row['commission_type'] === OrderCommission::COMMSSION_TYPE_SELLOUT;
            });
            $result[] = [
                'username' => $key, 
                OrderCommission::COMMSSION_TYPE_ORDER => array_sum(ArrayHelper::getColumn($commissionRows, 'user_commission')),
                OrderCommission::COMMSSION_TYPE_SELLOUT => array_sum(ArrayHelper::getColumn($selloutRows, 'user_commission'))
            ];
        }

        return $result;
    }
}
