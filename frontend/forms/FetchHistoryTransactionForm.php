<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use common\models\PaymentTransaction;

class FetchHistoryTransactionForm extends Model
{
    public $user_id;
    public $start_date;
    public $end_date;
    private $_command;

    public function rules()
    {
        return [
            ['user_id', 'required'],
            ['start_date', 'default', 'value' => date('Y-m-01')],
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
        $command = PaymentTransaction::find();
        $command->where(['user_id' => $this->user_id]);
        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
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
}
