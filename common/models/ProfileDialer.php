<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ProfileDialer extends ActiveRecord
{
    const SCENARIO_CALL = 'call';
	const SCENARIO_SMS = 'sms';
    public static function tableName()
    {
        return '{{%profile_dialer}}';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CALL => ['call', 'profile_id', 'dialer_id'],
            self::SCENARIO_SMS => ['viettel', 'mobifone', 'vinaphone', 'vinamobile', 'gmobile', 'other'],
        ];
    }

    public function rules()
    {
        return [
            [['profile_id', 'dialer_id'], 'required'],
            ['call', 'required', 'on' => self::SCENARIO_CALL],
        	[['viettel', 'mobifone', 'vinaphone', 'vinamobile', 'gmobile', 'other'], 'required', 'on' => self::SCENARIO_SMS],
        ];
    }
}
