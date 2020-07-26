<?php
namespace frontend\events;

use Yii;
use yii\base\Model;

class LoginHandler extends Model
{
	/**
	 * @param $event UserEvent
	 */
    public static function logLogin($event) 
    {
        $identity = $event->identity; // User
        $identity->touch('last_login');
    }
}