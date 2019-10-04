<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\PaymentTransaction;

class FetchTransactionForm extends PaymentTransaction
{
    public $created_at_from;
    public $created_at_to;
    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = PaymentTransaction::find();
        if ($this->id) {
            $command->andWhere(['id' => $this->id]);
        }
        if ($this->payment_type) {
            $command->andWhere(['payment_type' => $this->payment_type]);
        }
        if ($this->user_id) {
            $command->andWhere(['user_id' => $this->user_id]);
        }
        if ($this->remark) {
            $command->andWhere(['remark' => trim($this->remark)]);
        }
        if ($this->status) {
            $command->andWhere(['status' => $this->status]);
        }
        if ($this->created_at_from) {
            $command->andWhere(['>=', "created_at", $this->created_at_from]);
        }
        if ($this->created_at_to) {
            $command->andWhere(['<=', "created_at", $this->created_at_to]);
        }
        $command->with('user');
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
