<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class SupplierBank extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%supplier_bank}}';
    }

    public function getBank() 
    {
        return $this->hasOne(Bank::className(), ['code' => 'bank_code']);
    }
}