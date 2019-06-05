<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Record extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    const DIALER_TYPE_SMS = 'sms';
    const DIALER_TYPE_CALL = 'call';

    const STATUS_REQUESTING = 'requesting';
    const STATUS_CALLING = 'calling';
    const STATUS_END = 'end';

    public static function tableName()
    {
        return '{{%record}}';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['user_id', 'dialer_id', 'dialer_type', 'start_time', 'phone', 'message', 'status'],
            self::SCENARIO_EDIT => ['id', 'end_time', 'status'],
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'dialer_id', 'dialer_type', 'start_time', 'phone', 'message', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'end_time', 'status'], 'required', 'on' => self::SCENARIO_EDIT],
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

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getDialer()
    {
        return $this->hasOne(Dialer::className(), ['id' => 'dialer_id']);
    }

    public static function getTypeList()
    {
        return [
            self::DIALER_TYPE_SMS => 'Nhắn tin',
            self::DIALER_TYPE_CALL => 'Gọi thoại'
        ];
    }

    public function getDuration()
    {
        return (strtotime($this->end_time) - strtotime($this->start_time));
    }
}
