<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Payment;

class FetchPaymentForm extends Model
{
    public $id;
    public $status;
    public $payment_method;
    public $payment_type;
    public $user_id;
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
        $command = Payment::find();
        if ($this->id) {
            $command->andWhere(['id' => $this->id]);
        }
        if ($this->payment_method) {
            $command->andWhere(['payment_method' => $this->payment_method]);
        }
        if ($this->payment_type) {
            $command->andWhere(['payment_type' => $this->payment_type]);
        }
        if ($this->user_id) {
            $command->andWhere(['user_id' => $this->user_id]);
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

    public function fetchPaymentMethods()
    {
        return [
            'alipay' => 'Alipay',
            'skrill' => 'Skrill',
            'alipay' => 'Alipay',
            'wechat' => 'Wechat',
            'postal-savings-bank-of-china' => 'Postal Savings Bank Of China',
            'payoneer' => 'Payoneer',
            'bitcoin' => 'Bitcoin',
            'western_union' => 'Western Union',
            'neteller' => 'Neteller',
            'standard_chartered' => 'Standard Chartered',
        ];   
    }
}
