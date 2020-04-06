<?php
namespace common\models;

use Yii;
use yii\db\ActiveQuery;

class BankAccount extends BaseBankAccount
{
    public static function find()
    {
        return new BankAccountQuery(get_called_class());
    }
}

class BankAccountQuery extends ActiveQuery
{
    public function init()
    {
    	$table = BankAccount::tableName();
        $this->andOnCondition(["{$table}.bank_type" => BaseBankAccount::BANK_TYPE_BANK]);
        parent::init();
    }
}