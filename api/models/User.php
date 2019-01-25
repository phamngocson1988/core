<?php
namespace api\models;

use common\models\User as CommonUser;

class User extends CommonUser
{
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }
}