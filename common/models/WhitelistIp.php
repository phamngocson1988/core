<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class WhitelistIp extends ActiveRecord
{
    const STATUS_WAITING = 0;
    const STATUS_APPROVED = 1;

    public static function tableName()
    {
        return '{{%whitelist_ip}}';
    }

    public static function primaryKey()
    {
        return ["ip"];
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
	            'createdByAttribute' => null,
	            'updatedByAttribute' => 'updated_by'
	        ],
        ];
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }
}