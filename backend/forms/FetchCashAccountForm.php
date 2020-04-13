<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Cash;
use backend\models\CashAccount;
use common\components\helpers\CommonHelper;

class FetchCashAccountForm extends Model
{
    public $bank_id;
    public $account_number;

    private $_command;

    protected function createCommand()
    {
        $bankTable = Cash::tableName();
        $bankAccountTable = CashAccount::tableName();
        $command = CashAccount::find();
        $command->innerJoin($bankTable, "{$bankTable}.id = {$bankAccountTable}.bank_id");
        if ($this->bank_id) {
            $command->andWhere(["{$bankAccountTable}.bank_id" => $this->bank_id]);
        }
        if ($this->account_number) {
            $command->andWhere(["{$bankAccountTable}.account_number" => $this->account_number]);
        }
        // die($command->createCommand()->getRawSql());
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
        $banks = Cash::find()->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }
}
