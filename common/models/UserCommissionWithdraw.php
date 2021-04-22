<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

class UserCommissionWithdraw extends ActiveRecord
{
    const STATUS_REQUEST = 1;
    const STATUS_APPROVED = 2;
    const STATUS_EXECUTED = 3;

    public static $default_duration = 30; //days

	public static function tableName()
    {
        return '{{%affiliate_commission_withdraw}}';
    }

    public function getId()
    {
        return "R" . $this->id;
    }
    
    public static function getStatusList()
    {
        return [
            self::STATUS_REQUEST => 'Requested',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_EXECUTED => 'Executed',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public function getStatusLabel()
    {
        $list = self::getStatusList();
        return ArrayHelper::getValue($list, $this->status);
    }

    public function isRequest()
    {
        return $this->status == self::STATUS_REQUEST;
    }

    public function isApprove()
    {
        return $this->status == self::STATUS_APPROVED;
    }

    public function isExecuted()
    {
        return $this->status == self::STATUS_EXECUTED;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getAcceptor()
    {
        return $this->hasOne(User::className(), ['id' => 'approved_by']);
    }

    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executed_by']);
    }
}