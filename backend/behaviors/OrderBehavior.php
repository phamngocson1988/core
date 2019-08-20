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
        if (!$order->process_start_time) {
        	$order->process_start_time = date('Y-m-d H:i:s');
        }
        return $order->save();
    }
}
