<?php 
namespace website\components\notifications;

use Yii;
use yii\helpers\ArrayHelper;
use webzop\notifications\Notification;
use website\models\User;

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
     * @var \website\models\Order the order object
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
        $order = $this->order;
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
                self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE,
                self::NOTIFY_SUPPLIER_CANCEL_ORDER,
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
        $order = $this->order;

        $settings = Yii::$app->settings;
        $supplierMail = $settings->get('ApplicationSettingForm', 'supplier_service_email');
        $kinggemsMail = $settings->get('ApplicationSettingForm', 'customer_service_email');
        $supplierMailer = Yii::$app->supplier_mailer;
        $kinggemsMailer = Yii::$app->mailer;
        $user = User::findOne($this->userId);

        Yii::$app->urlManagerBackend->setHostInfo(Yii::$app->params['backend_url']);
        Yii::$app->urlManagerSupplier->setHostInfo(Yii::$app->params['supplier_url']);

        $fromEmail = $kinggemsMail;
        $mailer = $kinggemsMailer;
        $toEmail = $user->email;
        $subject = '';
        $template = '';
        $data = [];

        switch($this->key) {
            case self::NOTIFY_CUSTOMER_PENDING_ORDER:
                $subject = sprintf('King Gems - #%s - Order Confirmed', $this->order->id);
                $template = 'order_confirmed';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                break;
            case self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE:
                $subject = sprintf('Hoàng Gia - #%s - Tin nhắn mới', $this->order->id);
                $template = 'notify_supplier_new_message';
                $fromEmail = $supplierMail;
                $mailer = $supplierMailer;
                $supplierOrder = $this->order->workingSupplier;
                if (!$supplierOrder) return;
                $data['detailUrl'] = Yii::$app->urlManagerSupplier->createAbsoluteUrl(['order/edit', 'id' => $supplierOrder->id], true);
                break;
            case self::NOTIFY_SUPPLIER_CANCEL_ORDER:
                $subject = sprintf('Hoàng Gia - #%s - Yêu cầu hủy', $this->order->id);
                $template = 'notify_supplier_cancel_order';
                $fromEmail = $supplierMail;
                $mailer = $supplierMailer;
                $supplierOrder = $this->order->workingSupplier;
                if (!$supplierOrder) return;
                $data['detailUrl'] = Yii::$app->urlManagerSupplier->createAbsoluteUrl(['order/edit', 'id' => $supplierOrder->id], true);
                break;

        }

        $this->order->log(sprintf("website notification mail %s to %s", $this->key, $toEmail));
        $message = $mailer->compose($template, array_merge([
            'user' => $user,
            'order' => $this->order,
            'notification' => $this,
        ], $data));

        $message->setFrom($fromEmail);
        $message->setTo($toEmail);
        $message->setSubject($subject);
        $message->send($mailer);
    }
}