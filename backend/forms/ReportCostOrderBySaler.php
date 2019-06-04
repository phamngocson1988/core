<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use common\models\User;

class ReportCostOrderBySaler extends Model
{
    public $saler_id;
    public $start_date;
    public $end_date;

    private $_command;

    public function init()
    {
        if (!$this->start_date) $this->start_date = date('Y-m-d 00:00', strtotime('-29 days'));
        if (!$this->end_date) $this->end_date = date('Y-m-d 23:59');
    }

    public function rules()
    {
        return [
            ['saler_id', 'trim'],
            ['start_date', 'default', 'value' => date('Y-m-d 00:00', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d 23:59')],
            [['start_date', 'end_date'], 'required'],
        ];
    }

    public function createCommand()
    {
        $command = Order::find();
        $command->where(["IN", 'status', $this->completeStatus()]);
        $command->andWhere(['IS NOT', 'saler_id', null]);
        if ($this->saler_id) {
            $command->andWhere(['saler_id' => $this->saler_id]);
        }
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date]);
        }
        $command->select(['id', 'saler_id', 'SUM(game_pack) as game_pack', 'SUM(total_price) as total_price']);
        $command->with('saler');
        $command->groupBy('saler_id');
        return $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return clone $this->_command;
    }


    public function fetchUsers()
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

    public function availabelStatus()
    {
        return [
            Order::STATUS_VERIFYING,
            Order::STATUS_PENDING, 
            Order::STATUS_PROCESSING, 
            Order::STATUS_COMPLETED
        ];
    }

    public function unCompleteStatus()
    {
        return [
            // Order::STATUS_VERIFYING,
            Order::STATUS_PENDING, 
        ];
    }

    public function completeStatus()
    {
        return [
            Order::STATUS_PROCESSING, 
            Order::STATUS_COMPLETED
        ];
    }
}
