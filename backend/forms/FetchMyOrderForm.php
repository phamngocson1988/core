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
    
    public function init()
    {
        if (!$this->start_date) $this->start_date = date('Y-m-d 00:00', strtotime('-29 days'));
        if (!$this->end_date) $this->end_date = date('Y-m-d 23:59');
    }

    public function rules()
    {
        return [
            [['status'], 'safe'],
            ['start_date', 'default', 'value' => date('Y-m-d 00:00', strtotime('-29 days'))],
            ['end_date', 'default', 'value' => date('Y-m-d 23:59')],
            [['start_date', 'end_date'], 'required'],
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
            ['orderteam_id' =>  Yii::$app->user->id]
        ]);
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date]);
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
