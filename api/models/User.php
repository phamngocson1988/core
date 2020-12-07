<?php
namespace api\models;

use Yii;

class User extends \common\models\User
{
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function fields()
    {
        return [
            'id',
            'email',
            'username',
            'name',
            'firstname',
            'lastname',
            'reseller_level',
            'avatar' => function ($model) {
                return $model->getAvatarUrl();
            },
        ];
    }
}