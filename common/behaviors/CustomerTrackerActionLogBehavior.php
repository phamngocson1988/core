<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\CustomerTrackerActionLog;

class CustomerTrackerActionLogBehavior extends AttributeBehavior
{
    public function addAction($action, $content)
    {
        $owner = $this->owner; // LeadTracker
        $model = new CustomerTrackerActionLog();
        $model->lead_tracker_id = $owner->id;
        $model->action = $action;
        $model->content = $content;
        $model->save();
    }
}
