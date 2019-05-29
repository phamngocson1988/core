<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\TransactionHistory;
use common\models\User;

/**
 * FetchTransactionHistoryForm
 */
class FetchTransactionHistoryForm extends Model
{
    public $customer_id;
    public $q;
    public $transaction_type;
    public $start_date;
    public $end_date;

    protected $_customer;
    private $_command;

    public function rules()
    {
        return [
            [['q', 'customer_id', 'transaction_type', 'start_date', 'end_date'], 'trim'],
        ];
    }

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = TransactionHistory::find();
        if ($this->customer_id) {
            $command->andWhere(['user_id' => $this->customer_id]);
        }

        if ($this->q) {
            $command->andWhere(['like', 'description', $this->q]);
        }

        if ((string)$this->transaction_type !== "") {
            $command->andWhere(['transaction_type' => $this->transaction_type]);
        }

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

    public function getCustomer()
    {
        if (!$this->_customer) {
            $this->_customer = User::findOne($this->customer_id);
        }
        return $this->_customer;
    }
}
