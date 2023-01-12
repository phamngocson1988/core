<?php
namespace backend\models;

use Yii;
use backend\behaviors\CustomerTrackerActionLogBehavior;

class CustomerTracker extends \common\models\CustomerTracker
{
  public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['action'] = CustomerTrackerActionLogBehavior::className();
        return $behaviors;
    }
}