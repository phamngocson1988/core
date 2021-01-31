<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class CurrencySetting extends ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_DISACTIVE = 5;

    public static function tableName()
    {
        return '{{%currency}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public function isActive() 
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function isDisactive()
    {
        return $this->status == self::STATUS_DISACTIVE;
    }

    public static function fetchStatus()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DISACTIVE => 'Disactive',
        ];
    }

    public function getSymbol()
    {
        return $this->symbol ? $this->symbol : $this->code;
    }

    public function isFix()
    {
        return !!$this->is_fix;
    }

    public function getKcoin($number)
    {
        if (!$this->exchange_rate) return 0;
        return $number / $this->exchange_rate;
    }

    public function exchangeTo($number, $currency)
    {
        if ($this->code == $currency->code) return $number;
        $kcoin = $this->getKcoin($number);
        return $kcoin * $currency->exchange_rate;
    }
}