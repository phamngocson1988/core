<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class AffiliateCommission extends ActiveRecord
{
    const STATUS_VALID = 1;
    const STATUS_WITHDRAWED = 2;

	public static function tableName()
    {
        return '{{%affiliate_commission}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d')
            ],
        ];
    }

    public function isPending()
    {
        return ($this->status == self::STATUS_VALID) && (strtotime('now') < strtotime($this->valid_from_date));
    }

    public function isReady()
    {
        return ($this->status == self::STATUS_VALID) && (strtotime('now') >= strtotime($this->valid_from_date));
    }

    public function getStatusLabel()
    {
        if ($this->isPending()) return 'Pending';
        if ($this->isReady()) return 'Ready';
        return '';
    }

    public function isWithdrawed()
    {
        return $this->status == self::STATUS_WITHDRAWED;
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getMember()
    {
        return $this->hasOne(User::className(), ['id' => 'member_id']);
    }
}