<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class UserCommission extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%user_commission}}';
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
        ];
    }

    public function isPending()
    {
        return strtotime('now') < strtotime($this->valid_from_date);
    }

    public function isReady()
    {
        return strtotime('now') >= strtotime($this->valid_from_date);
    }
}