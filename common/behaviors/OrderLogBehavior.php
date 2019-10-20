<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\Order;
use common\models\OrderLog;

class OrderLogBehavior extends AttributeBehavior
{
    public function log($description)
    {
        $owner = $this->owner; // order
        $log = new OrderLog();
        $log->order_id = $owner->id;
        $log->description = $description;
        $log->save();
    }

}
