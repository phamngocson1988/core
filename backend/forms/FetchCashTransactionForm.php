<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\CashTransaction;
use backend\models\Cash;

class FetchCashTransactionForm extends Model
{
    public $bank_id;
    public $from_date;
    public $to_date;
    public $status;

    private $_command;

    protected function createCommand()
    {
        $table = CashTransaction::tableName();
        $command = CashTransaction::find();
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
        // die($command->createCommand()->getRawSql());
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
        $banks = Cash::find()->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }
}
