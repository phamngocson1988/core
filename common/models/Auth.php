<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Auth extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%auth}}';
    }
}