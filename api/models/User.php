<?php
namespace api\models;

use common\models\User as CommonUser;

class User extends CommonUser
{
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    public function exportData()
    {
    	$data = [
    		'id' => $this->id,
    		'name' => $this->name,
    		'username' => $this->username,
    		'avatar' => $this->avatar,
    		'email' => $this->email,
    		'status' => $this->status,
    		'created_at' => $this->created_at,
    		'updated_at' => $this->updated_at,
    	];
    	return (object)$data;
    }
}