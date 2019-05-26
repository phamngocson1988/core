<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use common\models\User;

class ReportProcessOrderByUser extends Model
{
    public $handler_id;
    public $start_date;
    public $end_date;

    private $_command;

    public function rules()
    {
        return [
            ['handler_id', 'trim'],
            ['start_date', 'default', 'value' => date('Y-m-d', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d')],
        ];
    }

    public function fetch()
    {
        // Find all user in period
        $status = $this->availabelStatus();
        $command = $this->getCommand();
        $command->select(['id', 'handler_id', 'SUM(game_pack) as game_pack']);
        $command->with('handler');
        $command->groupBy('handler_id');
        $command->andWhere(['IN', 'status', $status]);
        $command->andWhere(['IS NOT', 'handler_id', null]);
        $models = $command->indexBy('handler_id')->all();
        $users = [];
        
        // query report
        foreach ($models as $id => $order) {
            // pending order
            $penddingCommand = $this->getCommand();
            $penddingCommand->andWhere(['IN', 'status', $this->unCompleteStatus()]);
            $penddingCommand->andWhere(['handler_id' => $id]);
            $penddingCount = $penddingCommand->count();

            // completed order
            $completedCommand = $this->getCommand();
            $completedCommand->andWhere(['IN', 'status', $this->completeStatus()]);
            $completedCommand->andWhere(['handler_id' => $id]);
            $completedCount = $completedCommand->count();

            if (!$completedCount) {
                $rate = $avarageTime = 0;
            } else {
                $rate = $completedCount / ($completedCount + $penddingCount) * 100;
                $avarageTime = $completedCommand->sum('process_duration_time') / ($completedCount * 60); //mins
            }

            $users[$id]['name'] = ($order->handler) ? $order->handler->name : '';
            $users[$id]['game_pack'] = $order->game_pack;
            $users[$id]['completed_rate'] = $rate;
            $users[$id]['avarage_time'] = $avarageTime;
        }
        return $users;
    }

    public function createCommand()
    {
        $command = Order::find();
        $command->where("1=1");
        if ($this->handler_id) {
            $command->andWhere(['handler_id' => $this->handler_id]);
        }
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
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
        $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('handler');
        $managerTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam_manager');
        $adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');

        $orderTeamIds = array_merge($orderTeamIds, $managerTeamIds, $adminTeamIds);
        $orderTeamIds = array_unique($orderTeamIds);
        $orderTeamObjects = User::findAll($orderTeamIds);
        $orderTeam = ArrayHelper::map($orderTeamObjects, 'id', 'email');
        return $orderTeam;
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