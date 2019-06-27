<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;
use common\models\OrderItems;
use common\models\User;
use common\models\Game;

class ReportByUserForm extends Order
{
    public $type;
    public $start_date;
    public $end_date;

    public $count_order;

    public function rules()
    {
        return [
            [['type'], 'required'],
            ['start_date', 'default', 'value' => date('Y-m-01')],
            ['end_date', 'default', 'value' => date('Y-m-t')],
        ];
    }

    private $_command;
    
    public function fetch()
    {
        if (!$this->validate()) return [];
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = self::find();
        $command->select(['id', 'sum(total_price) as total_price', 'count(orderteam_id) as count_order', 'orderteam_id', 'saler_id']);
        $command->where(["IN", "status", [Order::STATUS_PROCESSING, Order::STATUS_COMPLETED]]);

        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        if ($this->type == 'orderteam') {
            $command->andWhere(['IS NOT', 'orderteam_id', null]);
            $command->groupBy('orderteam_id');
            $command->with('orderteam');
        } else if ($this->type == 'saler') {
            $command->andWhere(['IS NOT', 'saler_id', null]);
            $command->groupBy('saler_id');
            $command->with('saler');
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
}
