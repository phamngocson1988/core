<?php
namespace common\models;

use Yii;
use yii\db\ActiveQuery;

class BankTransaction extends ThreadTransaction
{
    public static function find()
    {
        return new BankTransactionQuery(get_called_class());
    }
}

class BankTransactionQuery extends ActiveQuery
{
    public function init()
    {
        $table = ThreadTransaction::tableName();
        $this->andOnCondition(["{$table}.transaction_type" => ThreadTransaction::TRANSACTION_TYPE_BANK]);
        parent::init();
    }
}
