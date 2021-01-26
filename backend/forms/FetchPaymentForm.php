<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\Payment;

class FetchPaymentForm extends Model
{
    public $paygate;
    public $payment_id;
    public $payment_type;
    public $payer;
    public $created_at_from;
    public $created_at_to;
    public $status;

    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Payment::find();
        $condition = [
            'paygate' => $this->paygate,
            'payment_type' => $this->payment_type,
        ];
        $condition = array_filter($condition);
        if (count($condition)) {
            $command->where($condition);
        }
        if ($this->payment_id) {
            $command->andWhere(['like', 'payment_id', $this->payment_id]);
        }

        if ($this->created_at_from) {
            $command->andWhere(['>=', "created_at", $this->created_at_from]);
        }

        if ($this->created_at_to) {
            $command->andWhere(['<=', "created_at", $this->created_at_to]);
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
