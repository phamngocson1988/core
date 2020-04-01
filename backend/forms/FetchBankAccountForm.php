<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Bank;
use backend\models\BankAccount;
use common\components\helpers\CommonHelper;

class FetchBankAccountForm extends Model
{
    public $bank_id;
    public $account_name;
    public $account_number;
    public $country;

    private $_command;

    protected function createCommand()
    {
        $bankTable = Bank::tableName();
        $bankAccountTable = BankAccount::tableName();
        $command = BankAccount::find();
        if ($this->bank_id) {
            $command->andWhere(["{$bankAccountTable}.bank_id" => $this->bank_id]);
        }
        if ($this->account_name) {
            $command->andWhere(["{$bankAccountTable}.like", "{$bankAccountTable}.account_name", $this->account_name]);
        }
        if ($this->account_number) {
            $command->andWhere(["{$bankAccountTable}.like", "{$bankAccountTable}.account_number", $this->account_number]);
        }

        if ($this->country) {
            $command->innerJoin($bankTable, "{$bankTable}.id = {$bankAccountTable}.bank_id");
            $command->andWhere(["{$bankTable}.country" => $this->country]);
        }
        $command->with('bank');
        
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function fetchCountry()
    {
        return CommonHelper::fetchCountry();
    }
    
    public function fetchBank()
    {
        $banks = Bank::find()->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }
}
