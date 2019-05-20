<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\PaymentTransaction;

class ReportByTransactionForm extends PaymentTransaction
{
    public $start_date;
    public $end_date;
    public $count_order;

    public function rules()
    {
        return [
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
        $command->where(["status" => self::STATUS_COMPLETED]);

        if ($this->start_date) {
            $command->andWhere(['>=', 'created_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'created_at', $this->end_date . " 23:59:59"]);
        }

        if ($this->user_id) {
            $command->andWhere(['user_id' => $this->user_id]);
        }

        if ($this->discount_code) {
            $command->andWhere(['discount_code' => $this->discount_code]);
        }

        if ($this->auth_key) {
            $command->andWhere(['auth_key' => $this->auth_key]);
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
