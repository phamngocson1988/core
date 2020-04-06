<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class BaseBank extends ActiveRecord
{
    const TRANSER_COST_TYPE_FIX = 'fix';
    const TRANSER_COST_TYPE_PERCENT = 'percent';

    const BANK_TYPE_BANK = 'bank';
    const BANK_TYPE_CASH = 'cash';
    
    public static function tableName()
    {
        return '{{%bank}}';
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
}