<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;

class UserFavorite extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%user_favorite}}';
    }
}