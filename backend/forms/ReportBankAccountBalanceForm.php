<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\BankAccount;
use backend\models\BankTransaction;

class ReportBankAccountBalanceForm extends Model
{
    public $currency;

    public function rules()
    {
        return [
            ['currency', 'required']
        ];
    }

    private $_command;

    protected function createCommand()
    {
        $table = BankTransaction::tableName();
        $command = BankTransaction::find();
        $command->where(["{$table}.status" => BankTransaction::STATUS_COMPLETED]);
        $command->andWhere(["{$table}.currency" => $this->currency]);
        $command->groupBy(["{$table}.bank_account_id"]);
        $command->select(["{$table}.bank_id", "{$table}.bank_account_id", "SUM({$table}.amount) AS amount"]);
        $command->orderBy(["amount" => SORT_DESC]);
        $command->with('bank');
        $command->with('bankAccount');
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