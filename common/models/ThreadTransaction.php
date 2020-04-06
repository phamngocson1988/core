<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class ThreadTransaction extends ActiveRecord
{
    const TYPE_IN = 'I';
    const TYPE_OUT = 'O';
    const TRANSACTION_TYPE_BANK = 'bank';
    const TRANSACTION_TYPE_CASH = 'cash';
    CONST STATUS_PENDING = 'pending';
    CONST STATUS_COMPLETED = 'completed';
    
    public static function tableName()
    {
        return '{{%thread_transaction}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public function getBank()
    {
        return $this->hasOne(BaseBank::className(), ['id' => 'bank_id']);
    }
    public function getBankAccount()
    {
        return $this->hasOne(BaseBankAccount::className(), ['id' => 'bank_account_id']);
    }

    public function isTypeIn()
    {
        return $this->type == self::TYPE_IN;
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function isCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
