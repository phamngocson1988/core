<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Order;

class FetchOrderForm extends Model
{
    public $id;
    public $customer_id;
    public $start_date;
    public $end_date;
    public $status;

    private $_command;

    public function rules()
    {
        return [
            ['customer_id', 'required'],
            [['id', 'start_date', 'end_date', 'status'], 'safe']
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
        $command->where(['customer_id' => $this->customer_id]);
        if ($this->id) {
            $command->andWhere(['id' => $this->id]);
        }
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        if ($this->status) {
            if ($this->status != Order::STATUS_PROCESSING) {
                $command->andWhere(['in', 'status', (array)$this->status]);
            } else {
                $command->andWhere(['in', 'status', [Order::STATUS_PROCESSING, Order::STATUS_PARTIAL]]);
            }
        } else {
            $listStatus = array_keys($this->fetchStatusList());
            $listStatus[] = Order::STATUS_PARTIAL;
            $command->andWhere(['in', 'status', $listStatus]);
        }
        // echo $command->createCommand()->getRawSql();
        $command->orderBy(['created_at' => SORT_DESC]);
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchStatusList()
    {
        return [
            Order::STATUS_PENDING => 'Pending',
            Order::STATUS_PROCESSING => 'Processing',
            Order::STATUS_COMPLETED => 'Completed',
            Order::STATUS_CONFIRMED => 'Confirmed',
            Order::STATUS_DELETED => 'Deleted',
            Order::STATUS_CANCELLED => 'Cancelled',
        ];
    }
}
