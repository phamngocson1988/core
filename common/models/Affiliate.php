<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

class Affiliate extends ActiveRecord
{
    const STATUS_DISABLE = 1;
    const STATUS_ENABLE = 2;

    public static function tableName()
    {
        return '{{%affiliate}}';
    }

    public static function primaryKey()
    {
        return ["user_id"];
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
                'createdAtAttribute' => 'updated_by',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public static function preferImList()
    {
        return [
            'Google Talk' => 'Google Talk',
            'MSN' => 'MSN',
            'Skype' => 'Skype',
            'Telegram' => 'Telegram',
            'QQ' => 'QQ',
            'WeChat' => 'WeChat',
            'WhatsApp' => 'WhatsApp',
        ];
    }

    public static function channelTypeList()
    {
        return [
            'Blog' => 'Blog',
            'Directory' => 'Directory',
            'E-Newsletter E-Mail' => 'E-Newsletter E-Mail',
            'Portal' => 'Portal',
            'Website' => 'Website',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function generateAffiliateCode()
    {
        $this->code = Yii::$app->security->generateRandomString(6);
    }

    public function isEnable()
    {
        return $this->status == self::STATUS_ENABLE;
    }
}