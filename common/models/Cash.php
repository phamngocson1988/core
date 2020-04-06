<?php
namespace common\models;

use Yii;
use yii\db\ActiveQuery;

class Cash extends BaseBank
{
    public static function find()
    {
        return new CashQuery(get_called_class());
    }
}

class CashQuery extends ActiveQuery
{
    public function init()
    {
    	$table = Cash::tableName();
        $this->andOnCondition(["{$table}.bank_type" => BaseBank::BANK_TYPE_CASH]);
        parent::init();
    }
}