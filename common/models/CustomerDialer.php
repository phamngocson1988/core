<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class CustomerDialer extends ActiveRecord
{
    const SCENARIO_CALL = 'call';
	const SCENARIO_SMS = 'sms';
    public static function tableName()
    {
        return '{{%customer_dialer}}';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CALL => ['customer_id', 'dialer_id', 'call'],
            self::SCENARIO_SMS => ['customer_id', 'dialer_id', 'viettel', 'mobifone', 'vinaphone', 'vinamobile', 'gmobile', 'other'],
        ];
    }

    public function rules()
    {
        return [
            [['customer_id', 'dialer_id'], 'required'],
            ['call', 'required', 'on' => self::SCENARIO_CALL],
        	[['viettel', 'mobifone', 'vinaphone', 'vinamobile', 'gmobile', 'other'], 'required', 'on' => self::SCENARIO_SMS],
        ];
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    public function getDialer()
    {
        return $this->hasOne(Dialer::className(), ['id' => 'dialer_id']);
    }
}
