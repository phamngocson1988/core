<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\UserWallet;

class ReportByBalanceForm extends UserWallet
{
    public $start_date;
    public $end_date;

    public function rules()
    {
        return [
            ['start_date', 'default', 'value' => date('Y-m-01')],
            ['end_date', 'default', 'value' => date('Y-m-t')],
        ];
    }

    private $_command;
    private $_input_command;
    private $_output_command;
    
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
            $command->andWhere(['>=', 'payment_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'payment_at', $this->end_date . " 23:59:59"]);
        }

        if ($this->user_id) {
            $command->andWhere(['user_id' => $this->user_id]);
        }

        $command->groupBy('user_id');
        $this->_command = $command;
    }

    protected function createInputCommand()
    {
        $command = $this->getCommand();
        $command->select(['id', 'sum(coin) as coin']);
        $command->andWhere(['type' => self::TYPE_INPUT]);
        $this->_input_command = $command;
    }

    protected function createOutputCommand()
    {
        $command = $this->getCommand();
        $command->select(['id', 'sum(coin) as coin']);
        $command->andWhere(['type' => self::TYPE_OUTPUT]);
        $this->_output_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function getInputCommand()
    {
        if (!$this->_input_command) {
            $this->createInputCommand();
        }
        return $this->_input_command;
    }

    public function getOutputCommand()
    {
        if (!$this->_output_command) {
            $this->createOutputCommand();
        }
        return $this->_output_command;
    }
}
