<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Bank extends ActiveRecord
{
	const STATUS_ACTIVE = 'Y';
    const STATUS_INACTIVE = 'N';

    public static function tableName()
    {
        return '{{%bank}}';
    }

    public static function primaryKey()
    {
        return ["code"];
    }

    public static function getVisibleStatus()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive'
        ];
    }
}