<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\PaymentReality;

class FetchPaymentRealityForm extends Model
{
    public $id;
    public $object_key;
    public $customer_id;
    public $payment_id;
    public $payer;
    public $status;
    public $date_type;
    public $start_date;
    public $end_date;

    private $_command;

    protected $_customer;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = PaymentReality::find();
        $condition = [
            'id' => preg_replace("/[^\d]/", "", $this->id),
            'object_key' => preg_replace("/[^\d]/", "", $this->object_key),
            'customer_id' => $this->customer_id,
            'payment_id' => $this->payment_id,
            'status' => $this->status,
        ];
        $condition = array_filter($condition);
        if (count($condition)) {
            $command->where($condition);
        }
        if ($this->payer) {
            $command->andWhere(['like', 'payer', $this->payer]);
        }

        if ($this->date_type) {
            switch ($this->date_type) {
                case 'created_at':
                case 'object_created_at':
                case 'payment_time': {
                    if ($this->start_date) {
                        $command->andWhere(['>=', $this->date_type, $this->start_date]);
                    }
            
                    if ($this->end_date) {
                        $command->andWhere(['<=', $this->date_type, $this->end_date]);
                    }
                    break;
                }
            }
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
            PaymentReality::STATUS_PENDING => 'Pending',
            PaymentReality::STATUS_CLAIMED => 'Claimed',
        ];
    }
}
