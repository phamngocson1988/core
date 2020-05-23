<?php 
namespace backend\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;

class OrderNotification extends Notification
{
    const NOTIFY_ORDERTEAM_NEW_PENDING = 'NOTIFY_ORDERTEAM_NEW_PENDING';
    const NOTIFY_SUPPLIER_NEW_ORDER = 'NOTIFY_SUPPLIER_NEW_ORDER';
    const NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE = 'NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE';
    const NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE = 'NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE';

    /**
     * @var \backend\models\Order the order object
     */
    public $order;

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key){
            case self::NOTIFY_ORDERTEAM_NEW_PENDING:
                return sprintf("[Đơn hàng mới] - #%s", $this->order->id);
            case self::NOTIFY_SUPPLIER_NEW_ORDER:
                return sprintf("[Đơn hàng mới] - #%s", $this->order->id);
            case self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE:
                return sprintf("[Information request] - #%s", $this->order->id);
            case self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE:
                return sprintf("[Tin nhắn mới] - #%s", $this->order->id);
        }
    }

    public function getDescription()
    {
        switch($this->key){
            case self::NOTIFY_ORDERTEAM_NEW_PENDING:
                return sprintf("Chờ phân phối");
            case self::NOTIFY_SUPPLIER_NEW_ORDER:
                return sprintf("Chờ nhận đơn");
            case self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE:
                return sprintf("Waiting for response");
            case self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE:
                return sprintf("Chờ phản hồi");
        }
    }

    public function getIcon()
    {
        switch($this->key){
            case self::NOTIFY_ORDERTEAM_NEW_PENDING:
            case self::NOTIFY_SUPPLIER_NEW_ORDER:
            case self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE:
            case self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE:
                return 'https://kinggems.us/images/logo_icon.png';

        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        switch($this->key){
            case self::NOTIFY_ORDERTEAM_NEW_PENDING:
                return '';
            case self::NOTIFY_SUPPLIER_NEW_ORDER:
                return ['order/waiting'];
            case self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE:
                return ['user/detail', 'id' => $this->order->id];
            case self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE:
                return ['order/edit', 'id' => $this->order->id];
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
                self::NOTIFY_ORDERTEAM_NEW_PENDING,
                self::NOTIFY_SUPPLIER_NEW_ORDER,
                self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE,
                self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE,
            ]
        ];
    }
}