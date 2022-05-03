<?php
namespace common\components\wings;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use common\models\Order;

/**
 * This service is for notifying changing order status to Wings system.
 * That's why the observer object must be Order
 */
class WingsBehavior extends Behavior
{
    public $observedStatus = [
        Order::STATUS_PENDING,
        Order::STATUS_PROCESSING,
        Order::STATUS_PARTIAL,
        Order::STATUS_COMPLETED,
        Order::STATUS_CONFIRMED,
        Order::STATUS_DELETED,
        Order::STATUS_CANCELLED
    ];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'notifyWingsChangeStatus',
        ];
    }
    public function notifyWingsChangeStatus($event)
    {
        $order = $this->owner;
        $oldAttributes = $event->changedAttributes;
        $oldStatus = ArrayHelper::getValue($oldAttributes, 'status');
        Yii::info('notifyWingsChangeStatus');
        Yii::info($order);
        Yii::info($oldStatus);
        if (!$order->reseller_id) return;
        if (!in_array($order->status, $this->observedStatus)) return;
        if ($order->status == $oldStatus) return;
        
        Yii::$app->queue->push(new WingsNotifyStatusJob([
            'id' => $order->id,
            'status' => $order->status
        ]));
    }
}
