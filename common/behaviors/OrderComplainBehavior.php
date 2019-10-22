<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\Order;
use common\models\OrderComplains;

class OrderComplainBehavior extends AttributeBehavior
{
    public function complain($content)
    {
        $owner = $this->owner; // order
        $model = new OrderComplains();
        $model->order_id = $owner->id;
        $model->content = $content;
        $model->save();
    }
}
