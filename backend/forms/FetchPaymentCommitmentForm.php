<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\PaymentCommitment;

class FetchPaymentCommitmentForm extends Model
{
    public $payment_id;
    public $object_key;
    public $customer_id;
    public $status;
    public $start_date;
    public $end_date;

    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = PaymentCommitment::find();
        $condition = [
            'payment_id' => $this->payment_id,
            'object_key' => preg_replace("/[^\d]/", "", $this->object_key),
            'customer_id' => $this->customer_id,
            'status' => $this->status,
        ];
        $condition = array_filter($condition);
        if (count($condition)) {
            $command->where($condition);
        }

        if ($this->start_date) {
            $command->andWhere(['>=', "created_at", $this->start_date]);
        }

        if ($this->end_date) {
            $command->andWhere(['<=', "created_at", $this->end_date]);
        }

        $command->orderBy(['status' => SORT_ASC]);
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function getCustomer()
    {
        if (!$this->_customer) {
            $this->_customer = User::findOne($this->customer_id);
        }
        return $this->_customer;
    }

    public function fetchStatus()
    {
        return [
            PaymentCommitment::STATUS_PENDING => 'Pending',
            PaymentCommitment::STATUS_APPROVED => 'Approved',
        ];
    }
}
