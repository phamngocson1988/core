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
        $roles = $identity->getRoles();
        $role = reset($roles);
        $request = Yii::$app->request;
        $log = new LoginLog([
            'user_id' => $identity->id,
            'role' => $role,
            'ip' => $request->userIP,
            'browser' => '',
            'device' => '',
            'location' => '',
        ]);
        $log->save();
    }

	/**
	 * @param $event UserEvent
	 */
    public static function afterLogout($event)
    {
    	$sender = $event->sender; // web\User;
        $identity = $event->identity; // User
        $fixRoles = $sender->fixRoles;
        $userRoles = $identity->getRoles();
        $intersect = array_intersect($fixRoles, $userRoles);
        if (!count($intersect)) {
            $auth = Yii::$app->authManager;
	        $auth->revokeAll($identity->id);
        }
    }
}