<?php
namespace common\behaviors;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use common\models\UserLog;

class UserLogBehavior extends AttributeBehavior
{
    public function getLastActivity()
    {
        $owner = $this->owner; // User
        return $owner->hasOne(UserLog::className(), ['user_id' => 'id']);
    }
    public function logLastActivity() 
    {
        $owner = $this->owner; // User
        $log = $owner->lastActivity;
        if (!$log ) {
            $log = new UserLog(['user_id' => $owner->id]);
            $log->save();
        } else {
            $log->touch('last_activity');
        }
    }
}
