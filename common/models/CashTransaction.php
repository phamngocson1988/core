<?php
namespace common\models;

use Yii;
use yii\db\ActiveQuery;

class CashTransaction extends ThreadTransaction
{
	public static function find()
    {
        return new CashTransactionQuery(get_called_class());
    }
}

class CashTransactionQuery extends ActiveQuery
{
    public function init()
    {
    	$table = ThreadTransaction::tableName();
        $this->andOnCondition(["{$table}.transaction_type" => ThreadTransaction::TRANSACTION_TYPE_CASH]);
        parent::init();
    }
}