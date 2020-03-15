<?php
namespace backend\models;

use yii\helpers\ArrayHelper;

class User extends \common\models\User
{
	public static function getUserStatus()
    {
        return [
            self::STATUS_ACTIVE => 'Kích hoạt',
            self::STATUS_DELETED => 'Tạm khóa',
        ];
    }

    public function getStatusLabel()
    {
        $labels = self::getUserStatus();
        return ArrayHelper::getValue($labels, $this->status);
    }
}