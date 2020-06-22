<?php 
namespace frontend\components\notifications;

use Yii;
use yii\helpers\ArrayHelper;
use webzop\notifications\Notification;
use frontend\models\User;

class OrderNotification extends Notification
{
    const NOTIFY_SALER_NEW_ORDER = 'NOTIFY_SALER_NEW_ORDER';
    const NOTIFY_ORDERTEAM_NEW_ORDER = 'NOTIFY_ORDERTEAM_NEW_ORDER';
    const NOTIFY_SALER_CANCEL_ORDER = 'NOTIFY_SALER_CANCEL_ORDER';
    const NOTIFY_ORDERTEAM_CANCEL_ORDER = 'NOTIFY_ORDERTEAM_CANCEL_ORDER';
    const NOTIFY_SUPPLIER_CANCEL_ORDER = 'NOTIFY_SUPPLIER_CANCEL_ORDER';
    const NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE = 'NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE';
    // for mail
    const NOTIFY_CUSTOMER_PENDING_ORDER = 'NOTIFY_CUSTOMER_PENDING_ORDER';
    /**
     * @var \frontend\models\Order the order object
     */
    public $order;

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
            case self::NOTIFY_ORDERTEAM_NEW_ORDER:
                return sprintf("[Đơn hàng mới] - #%s", $this->order->id);
            case self::NOTIFY_SALER_CANCEL_ORDER:
            case self::NOTIFY_ORDERTEAM_CANCEL_ORDER:
            case self::NOTIFY_SUPPLIER_CANCEL_ORDER:
                return sprintf("[Yêu cầu hủy] - #%s", $this->order->id);
            case self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE:
                return sprintf("[Tin nhắn mới] - #%s", $this->order->id);

        }
    }

    public function getIcon()
    {
        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
            case self::NOTIFY_ORDERTEAM_NEW_ORDER:
            case self::NOTIFY_SALER_CANCEL_ORDER:
            case self::NOTIFY_ORDERTEAM_CANCEL_ORDER:
                return 'https://kinggems.us/images/logo_icon.png';
            case self::NOTIFY_SUPPLIER_CANCEL_ORDER:
            case self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE:
                return 'http://hoanggianapgame.com/images/Logo-hoanggia-game.png';

        }
    }

    public function getDescription()
    {

        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
                return sprintf("Chờ duyệt giao dịch");
            case self::NOTIFY_ORDERTEAM_NEW_ORDER:
                return sprintf("Chờ phân phối");
            case self::NOTIFY_SALER_CANCEL_ORDER:
            case self::NOTIFY_ORDERTEAM_CANCEL_ORDER:
            case self::NOTIFY_SUPPLIER_CANCEL_ORDER:
                return sprintf("Yêu cầu hủy đơn hàng");
            case self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE:
                return sprintf("Chờ phản hồi");
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
            case self::NOTIFY_ORDERTEAM_NEW_ORDER:
            case self::NOTIFY_SALER_CANCEL_ORDER:
            case self::NOTIFY_ORDERTEAM_CANCEL_ORDER:
                return '';
            case self::NOTIFY_SUPPLIER_CANCEL_ORDER:
            case self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE:
                $supplier = $this->order->workingSupplier;
                return ['order/edit', 'id' => $supplier->id];
        }
    }

    public function shouldSend($channel)
    {
        $allows = $this->allow();
        $allowByChannel = ArrayHelper::getValue($allows, $channel->id, []);
        $re = in_array($this->key, $allowByChannel);
        return $re;
    }

    protected function allow()
    {
        return [
            'desktop' => [
                self::NOTIFY_SALER_NEW_ORDER,
                self::NOTIFY_ORDERTEAM_NEW_ORDER,
                self::NOTIFY_SALER_CANCEL_ORDER,
                self::NOTIFY_ORDERTEAM_CANCEL_ORDER,
                self::NOTIFY_SUPPLIER_CANCEL_ORDER,
                self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE,
            ],
            'email' => [
                self::NOTIFY_CUSTOMER_PENDING_ORDER,
            ],

        ];
    }

    /**
     * Override send to email channel
     *
     * @param $channel the email channel
     * @return void
     */
    public function toEmail($channel)
    {
        $settings = Yii::$app->settings;
        $supplierMail = $settings->get('ApplicationSettingForm', 'supplier_service_email');
        $supportMail = $settings->get('ApplicationSettingForm', 'customer_service_email');
        $fromEmail = $supportMail;
        $user = User::findOne($this->userId);

        switch($this->key) {
            case self::NOTIFY_CUSTOMER_PENDING_ORDER:
                $subject = sprintf('King Gems - #%s - Order Confirmed', $this->order->id);
                $template = 'new_pending_order';
                $fromEmail = $supportMail;
                break;
        }
        $message = $channel->mailer->compose($template, [
            'user' => $user,
            'order' => $this->order,
            'notification' => $this,
        ]);

        $message->setFrom($fromEmail);
        $message->setTo($user->email);
        $message->setSubject($subject);
        $message->send($channel->mailer);
    }
}