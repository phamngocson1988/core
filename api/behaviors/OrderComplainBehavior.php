<?php
namespace api\behaviors;

use api\models\OrderComplains;
use api\models\Order;

class OrderComplainBehavior extends \common\behaviors\OrderComplainBehavior
{
    public function complain($content, $type = 'text')
    {
        $owner = $this->owner; // order
        $model = new OrderComplains();
        $model->order_id = $owner->id;
        $model->content = $content;
        $model->content_type = $type;
        $model->object_name = OrderComplains::OBJECT_NAME_CUSTOMER;
        $model->save();

        $owner->state = Order::STATE_PENDING_CONFIRMATION;
        $owner->save();
    }
}
