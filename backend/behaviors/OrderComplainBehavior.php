<?php
namespace backend\behaviors;

use Yii;
use backend\models\OrderComplains;
use backend\models\Order;

class OrderComplainBehavior extends \common\behaviors\OrderComplainBehavior
{
    public function complain($content)
    {
        $owner = $this->owner; // order
        $model = new OrderComplains();
        $model->order_id = $owner->id;
        $model->content = $content;
        $model->object_name = OrderComplains::OBJECT_NAME_ADMIN;
        $model->save();
        $owner->state = Yii::$app->user->can('saler') ? Order::STATE_PENDING_CONFIRMATION : Order::STATE_PENDING_INFORMATION;
        $owner->save();
    }
}
