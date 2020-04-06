<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class BaseBankAccount extends ActiveRecord
{
    const BANK_TYPE_BANK = 'bank';
    const BANK_TYPE_CASH = 'cash';
    
    public static function tableName()
    {
        return '{{%bank_account}}';
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
}
