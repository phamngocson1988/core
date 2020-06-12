<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\PaymentTransaction;

class FetchPaymentTransactionForm extends Model
{
    public $user_id;
    public $start_date;
    public $end_date;
    public $status;

    private $_command;

    public function rules()
    {
        return [
            ['user_id', 'required'],
            [['start_date', 'end_date'], 'safe']
        ];
    }
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = PaymentTransaction::find();
        $command->where(['user_id' => $this->user_id]);
        $command->andWhere(['<>', 'status', PaymentTransaction::STATUS_PENDING]);
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }
        if ($this->status) {
            $command->andWhere(['status' => $this->status]);
        }
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
            PaymentTransaction::STATUS_COMPLETED => 'Completed',
            PaymentTransaction::STATUS_DELETED => 'Deleted',
        ];
    }

}
