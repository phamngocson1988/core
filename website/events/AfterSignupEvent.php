<?php
namespace website\events;

use Yii;
use yii\base\Event;

class AfterSignupEvent extends Event
{
    /** 
     * The user which has just created
     * @var instanceof \common\models\User
     */
    public $user;
}