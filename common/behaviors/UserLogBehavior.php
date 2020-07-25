<?php
namespace common\behaviors;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use common\models\UserLog;

class UserLogBehavior extends AttributeBehavior
{
    public function log($object, $action = '', $description = '') 
    {
        $owner = $this->owner; // User
        $log = new UserLog();
        $log->user_id = $owner->id;
        $log->object = $object;
        $log->action = $action;
        $log->description = $description;
        return $log->save();
    }
}
