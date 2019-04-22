<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;
use common\models\OrderItems;
use common\models\User;
use common\models\Game;

class MyCustomerReportForm extends Order
{
    public $user_id;
    public $start_date;
    public $end_date;

    public $count_order;

    public function rules()
    {
        return [
            [['user_id'], 'required'],
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
        $command->select(['id', 'sum(total_price) as total_price', 'count(id) as count_order', 'customer_id']);
        $command->where(["IN", "status", $this->getReportStatus()]);

        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        $command->andWhere(['or',
            ['handler_id' => $this->user_id],
            ['saler_id' => $this->user_id],
        ]);
        $command->groupBy('customer_id');
        $command->with('customer');

        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function getReportStatus()
    {
        return [Order::STATUS_PROCESSING, Order::STATUS_COMPLETED];
    }
}
