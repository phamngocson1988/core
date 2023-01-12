<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\LeadTrackerComment;

class LeadTrackerCommentBehavior extends AttributeBehavior
{
    public function addComment($content)
    {
        $owner = $this->owner; // LeadTracker
        $model = new LeadTrackerComment();
        $model->lead_tracker_id = $owner->id;
        $model->content = $content;
        $model->save();
    }
}
