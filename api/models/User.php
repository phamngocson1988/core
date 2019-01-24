<?php
namespace api\models;

use common\models\User as CommonUser;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

class User extends CommonUser
{
	public function behaviors()
    {
        $behaviors = parent::behaviors();
	    $behaviors['authenticator'] = [
	        'class' => CompositeAuth::className(),
	        'authMethods' => [
	            HttpBearerAuth::className(),
	        ],
	    ];
	    return $behaviors;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
}