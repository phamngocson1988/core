<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class OperatorStaff extends ActiveRecord
{
	const ROLE_ADMIN = 10;
	const ROLE_SUBADMIN = 9;
	const ROLE_MODERATOR = 8;

    public static function tableName()
    {
        return '{{%operator_staff}}';
    }
}