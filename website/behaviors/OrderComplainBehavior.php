<?php
namespace frontend\behaviors;

use frontend\models\OrderComplains;
use frontend\models\Order;

class OrderComplainBehavior extends \common\behaviors\OrderComplainBehavior
{
    public function complain($content)
    {
        $owner = $this->owner; // order
        $model = new OrderComplains();
        $model->order_id = $owner->id;
        $model->content = $content;
        $model->object_name = OrderComplains::OBJECT_NAME_CUSTOMER;
        $model->save();

        $owner->state = Order::STATE_PENDING_CONFIRMATION;
        $owner->save();
    }
}
