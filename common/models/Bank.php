<?php
namespace common\models;

use Yii;
use yii\db\ActiveQuery;

class Bank extends BaseBank
{
    public static function find()
    {
        return new BankQuery(get_called_class());
    }
}

class BankQuery extends ActiveQuery
{
    public function init()
    {
    	$table = Bank::tableName();
        $this->andOnCondition(["{$table}.bank_type" => BaseBank::BANK_TYPE_BANK]);
        parent::init();
    }
}