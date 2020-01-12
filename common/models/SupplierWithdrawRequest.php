<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class SupplierWithdrawRequest extends ActiveRecord
{
    CONST STATUS_REQUEST = "request";
    const STATUS_APPROVE = "approve";
    const STATUS_EXECUTE = "execute";
    const STATUS_CANCEL = "cancel";

    public static function tableName()
    {
        return '{{%supplier_withdraw_request}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public function getBankAccount()
    {
    	return $this->hasOne(SupplierBank::className(), ['id' => 'bank_id']);
    }

    public function isRequest()
    {
    	return $this->status == self::STATUS_REQUEST;
    }
}
