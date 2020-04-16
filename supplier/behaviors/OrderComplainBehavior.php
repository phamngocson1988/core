<?php
namespace supplier\behaviors;

use supplier\models\OrderComplains;
use supplier\models\Order;

class OrderComplainBehavior extends \common\behaviors\OrderComplainBehavior
{
    public function complain($content)
    {
        $owner = $this->owner; // order
        $model = new OrderComplains();
        $model->order_id = $owner->id;
        $model->content = $content;
        $model->object_name = OrderComplains::OBJECT_NAME_SUPPLIER;
        $model->save();

        $owner->state = Order::STATE_PENDING_INFORMATION;
        $owner->save();
    }
}
