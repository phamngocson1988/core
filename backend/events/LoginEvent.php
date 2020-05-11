<?php
namespace backend\events;

use Yii;
use yii\base\Model;

class LoginEvent extends Model
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