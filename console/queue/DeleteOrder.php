<?php
namespace console\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Order;

class DeleteOrder extends BaseObject implements \yii\queue\JobInterface
{
    public $id;

    public function execute($queue)
    {
        $order = Order::findOne($this->id);
        if ($order) $order->delete();
    }
}