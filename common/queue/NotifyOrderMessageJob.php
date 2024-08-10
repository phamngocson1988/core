<?php
namespace common\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Tracking;
use common\models\Order;
use common\models\OrderComplains;
use yii\helpers\ArrayHelper;

class NotifyOrderMessageJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var int - order id
     */
    public $orderId;

    /**
     * @var int - message id
     */
    public $messageId;
    
    public function execute($queue)
    {
        try {
            $order = $this->getOrder();
            $message = $this->getMessage();
            $sender = $message->sender;
            if ($order->reseller_id) {
                $key = sprintf("order_data:%s:messages:%s", $order->id, $message->id);
                $value = [
                    'id' => $message->id,
                    'sublink_customer_id' => $message->user_sublink_id,
                    'server_order_id' => $order->id,
                    'sender' => $message->object_name, // server, supplier, customer
                    'sender_name' => $sender->getName(),
                    'sender_avatar' => $sender->getAvatarUrl(),
                    'content' => $message->content,
                    'content_type' => $message->content_type, // text, image, file
                    'created_at' => $message->created_at
                ];
                Yii::$app->redis->set($key, json_encode($value));
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->handleLog("fail process $e->getMessage()");
        }
    }

    protected function getOrder()
    {
        return Order::findOne($this->orderId);
    }

    protected function getMessage()
    {
        return OrderComplains::findOne($this->messageId);
    }

    protected function handleQueueError($errors)
    {
        $track = new Tracking();
        $description = sprintf("%s %s fail %s", __CLASS__, $this->order->id, json_encode($errors));
        print_r($description);

        $track->description = $description;
        $track->save();
    }

    protected function handleLog($data)
    {
        $track = new Tracking();
        $description = sprintf("%s: %s", __CLASS__, $data);
        $track->description = $description;
        $track->save();
    }
}