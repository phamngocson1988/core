<?php
namespace backend\models;

use Yii;
use backend\behaviors\LeadTrackerCommentBehavior;

class LeadTracker extends \common\models\LeadTracker
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['comment'] = LeadTrackerCommentBehavior::className();
        return $behaviors;
    }
}