<?php
namespace backend\behaviors;

use yii\behaviors\AttributeBehavior;
use backend\models\Order;

class OrderBehavior extends AttributeBehavior
{
    public function assignOrderTeam($id)
    {
        $order = $this->owner;
        $order->orderteam_id = $id;
        return $order->save();
    }
}
