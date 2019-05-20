<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;

class FetchMyOrderForm extends Model
{
    public $start_date;
    public $end_date;
    public $status;
    private $_command;
    
    public function rules()
    {
        return [
            [['start_date', 'end_date', 'status'], 'safe'],
            ['start_date', 'default', 'value' => date('Y-m-d', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d')],
        ];
    }

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Order::find();
        $command->where(["<>", "status", Order::STATUS_DELETED]);
        $command->andWhere(['OR',
            ['saler_id' => Yii::$app->user->id],
            ['handler_id' =>  Yii::$app->user->id]
        ]);
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        if ($this->status) {
            if (is_array($this->status)) {
                $command->andWhere(['IN', 'status', $this->status]);
            } else {
                $command->andWhere(['status' => $this->status]);
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

    public function getStatus()
    {
        $list = Order::getStatusList();
        unset($list[Order::STATUS_DELETED]);
        return $list;
    }
}
