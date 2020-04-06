<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\BankTransaction;
use backend\models\Bank;

class FetchBankTransactionForm extends Model
{
    public $bank_id;
    public $from_date;
    public $to_date;
    public $status;

    private $_command;

    protected function createCommand()
    {
        $table = BankTransaction::tableName();
        $command = BankTransaction::find();
        if ($this->bank_id) {
            $command->andWhere(["{$table}.bank_id" => $this->bank_id]);
        }
        if ($this->status) {
            $command->andWhere(["{$table}.status" => $this->status]);
        }
        if ($this->from_date) {
            $command->andWhere([">=", "{$table}.created_at", sprintf("%s 00:00:00", $this->from_date)]);
        }
        if ($this->to_date) {
            $command->andWhere(["<=", "{$table}.created_at", sprintf("%s 23:59:59", $this->to_date)]);
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
    
    public function fetchBank()
    {
        $banks = Bank::find()->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }
}
