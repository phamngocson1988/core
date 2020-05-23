<?php 
namespace supplier\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;

class OrderNotification extends Notification
{
    const NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE = 'NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE';

    /**
     * @var \supplier\models\Order the order object
     */
    public $order;

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key){
            case self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE:
                return sprintf("[Information request] - #%s", $this->order->id);
        }
    }

    public function getDescription()
    {
        switch($this->key){
            case self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE:
                return sprintf("Waiting for response");
        }
    }

    public function getIcon()
    {
        switch($this->key){
            case self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE:
                return 'https://kinggems.us/images/logo_icon.png';

        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        switch($this->key){
            case self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE:
                return ['user/detail', 'id' => $this->order->id];
        }
    }

    public function shouldSend($channel)
    {
        $allows = $this->allow();
        $allowByChannel = ArrayHelper::getValue($allows, $channel->id, []);
        return in_array($this->key, $allowByChannel);
    }

    protected function allow()
    {
        return [
            'desktop' => [
                self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE,
            ]
        ];
    }
}