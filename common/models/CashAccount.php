<?php
namespace common\models;

use yii\db\ActiveQuery;
use Yii;

class CashAccount extends BaseBankAccount
{
    const ROOT_ACCOUNT = 'Y';
    
	public static function find()
    {
        return new CashAccountQuery(get_called_class());
    }

    public function isRoot()
    {
        return $this->root == self::ROOT_ACCOUNT;
    }
}

class CashAccountQuery extends ActiveQuery
{
    public function init()
    {
    	$table = CashAccount::tableName();
        $this->andOnCondition(["{$table}.bank_type" => BaseBankAccount::BANK_TYPE_CASH]);
        parent::init();
    }
}