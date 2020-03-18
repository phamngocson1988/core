<?php
namespace backend\events;

use Yii;
use yii\base\Model;
use common\models\LoginLog;

class LoginEvent extends Model
{
	/**
	 * @param $event UserEvent
	 */
    public static function logLogin($event) 
    {
        $identity = $event->identity; // User
        $request = Yii::$app->request;
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($identity->id);
        $roleNames = array_column($roles, 'name');
        $log = new LoginLog([
            'user_id' => $identity->id,
            'role' => implode(", ", $roleNames),
            'ip' => $request->userIP,
            'browser' => '',
            'device' => '',
            'location' => '',
        ]);
        $log->save();
    }
}