<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\OrderSupplier;
use backend\models\User;
use dosamigos\chartjs\ChartJs;

class ReportSalerProfitForm extends Model
{
    public $saler_id;
    public $confirmed_from;
    public $confirmed_to;

    private $_command;

    public function rules() 
    {
        return [
            ['saler_id', 'required', 'message' => 'Bạn cần chọn 1 nhân viên'],
            [['confirmed_from', 'confirmed_to'], 'safe']
        ];
    }

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
        $command = Order::find();
        $command->andWhere(["{$orderTable}.status" => Order::STATUS_CONFIRMED]);
        if ($this->confirmed_from) {
            $command->andWhere(['>=', "{$orderTable}.confirmed_at", $this->confirmed_from]);
        }
        if ($this->confirmed_to) {
            $command->andWhere(['<=', "{$orderTable}.confirmed_at", $this->confirmed_to]);
        }

        if ($this->saler_id) {
            $command->andWhere(["{$orderTable}.saler_id" => $this->saler_id]);
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

    public function fetchUsers()
    {
        $auth = Yii::$app->authManager;
        $salerTeamIds = $auth->getUserIdsByRole('saler');
        $salerTeamManagerIds = $auth->getUserIdsByRole('sale_manager');
        $adminTeamIds = $auth->getUserIdsByRole('admin');

        $userIds = array_merge($salerTeamIds, $salerTeamManagerIds, $adminTeamIds);
        $userIds = array_unique($userIds);
        $users = User::findAll($userIds);
        return ArrayHelper::map($users, 'id', 'email');
    }
}
