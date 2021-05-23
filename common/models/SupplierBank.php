<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class SupplierBank extends ActiveRecord
{
    const VERIFIED_NO = 'N';
    const VERIFIED_YES = 'Y';

    public static function tableName()
    {
        return '{{%supplier_bank}}';
    }

    public function getBank() 
    {
        return $this->hasOne(Bank::className(), ['code' => 'bank_code']);
    }

    public function isNotVerified()
    {
        return $this->verified === self::VERIFIED_NO;
    }
}