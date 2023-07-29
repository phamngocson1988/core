<?php 
namespace backend\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;
use backend\models\User;

class OrderNotification extends Notification
{
    const NOTIFY_ORDERTEAM_NEW_PENDING = 'NOTIFY_ORDERTEAM_NEW_PENDING';
    const NOTIFY_SUPPLIER_NEW_ORDER = 'NOTIFY_SUPPLIER_NEW_ORDER'; // notification and mail
    const NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE = 'NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE';
    const NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE = 'NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE';
    const NOTIFY_CUSTOMER_COMPLETE_ORDER = 'NOTIFY_CUSTOMER_COMPLETE_ORDER';
    const NOTIFY_CUSTOMER_PENDING_ORDER = 'NOTIFY_CUSTOMER_PENDING_ORDER';
    const NOTIFY_CUSTOMER_CANCELLATION_ACCEPTED_ORDER = 'NOTIFY_CUSTOMER_CANCELLATION_ACCEPTED_ORDER';
    const NOTIFY_CUSTOMER_CANCELLATION_DENIED_ORDER = 'NOTIFY_CUSTOMER_CANCELLATION_DENIED_ORDER';

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
            case self::NOTIFY_CUSTOMER_COMPLETE_ORDER:
                return sprintf("[Order completed] - #%s", $this->order->id);
            case self::NOTIFY_CUSTOMER_CANCELLATION_ACCEPTED_ORDER:
                return sprintf("[Order cancelled] - #%s", $this->order->id);
            case self::NOTIFY_CUSTOMER_PENDING_ORDER:
                return sprintf("[Order approved] - #%s", $this->order->id);
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
            case self::NOTIFY_CUSTOMER_COMPLETE_ORDER:
                return sprintf("Order completed");
            case self::NOTIFY_CUSTOMER_CANCELLATION_ACCEPTED_ORDER:
                return sprintf("Order cancelled");
            case self::NOTIFY_CUSTOMER_PENDING_ORDER:
                return sprintf("Order approved");
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
                return ['order/index', '#' => $this->order->id];
            case self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE:
                return ['order/edit', 'id' => $this->order->id];
            case self::NOTIFY_CUSTOMER_COMPLETE_ORDER:
                return ['order/index', '#' => $this->order->id];
            case self::NOTIFY_CUSTOMER_CANCELLATION_ACCEPTED_ORDER:
                return ['order/index', '#' => $this->order->id];
            case self::NOTIFY_CUSTOMER_PENDING_ORDER:
                return ['order/index', '#' => $this->order->id];
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
            ],
            'email' => [
                self::NOTIFY_SUPPLIER_NEW_ORDER,
                self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE,
                self::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE,
                self::NOTIFY_CUSTOMER_COMPLETE_ORDER,
                self::NOTIFY_CUSTOMER_PENDING_ORDER,
                self::NOTIFY_CUSTOMER_CANCELLATION_ACCEPTED_ORDER,
                self::NOTIFY_CUSTOMER_CANCELLATION_DENIED_ORDER,
            ],
            'screen' => [
                self::NOTIFY_CUSTOMER_COMPLETE_ORDER,
                self::NOTIFY_CUSTOMER_CANCELLATION_ACCEPTED_ORDER,
                self::NOTIFY_CUSTOMER_PENDING_ORDER,
            ]
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
        $kinggemsMail = $settings->get('ApplicationSettingForm', 'customer_service_email');
        $supplierMailer = Yii::$app->supplier_mailer;
        $kinggemsMailer = Yii::$app->mailer;
        $user = User::findOne($this->userId);
        Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
        Yii::$app->urlManagerSupplier->setHostInfo(Yii::$app->params['supplier_url']);

        $fromEmail = $kinggemsMail;
        $mailer = $kinggemsMailer;
        $toEmail = $user->email;
        $subject = '';
        $template = '';
        $data = [];

        switch($this->key) {
            case self::NOTIFY_SUPPLIER_NEW_ORDER:
                $subject = sprintf('Hoàng Gia - #%s - Đơn hàng mới', $this->order->id);
                $template = 'notify_supplier_new_order';
                $fromEmail = $supplierMail;
                $mailer = $supplierMailer;
                $data['orderWaitingUrl'] = Yii::$app->urlManagerSupplier->createAbsoluteUrl(['order/waiting'], true);
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
            case self::NOTIFY_CUSTOMER_PENDING_ORDER:
                $subject = sprintf('KingGems - #%s - Order Confirmed', $this->order->id);
                $template = 'order_confirmed';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                break;
            case self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE:
                $subject = sprintf('KingGems - #%s - Information Request', $this->order->id);
                $template = 'infomation_request';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                $data['detailUrl'] = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['order/index', '#' => $this->order->id], true);
                $data['message'] = ArrayHelper::getValue($this->data, 'message', '');
                break;
            case self::NOTIFY_CUSTOMER_COMPLETE_ORDER:
                $subject = sprintf('KingGems - #%s - Completed', $this->order->id);
                $template = 'completed';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                $data['detailUrl'] = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['order/index', '#' => $this->order->id], true);
                break;
            case self::NOTIFY_CUSTOMER_CANCELLATION_ACCEPTED_ORDER:
                $subject = sprintf('KingGems - #%s - Cancellation Accepted', $this->order->id);
                $template = 'cancellation_accepted';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                $data['detailUrl'] = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['order/index', '#' => $this->order->id], true);
                break;
            case self::NOTIFY_CUSTOMER_CANCELLATION_DENIED_ORDER:
                $subject = sprintf('KingGems - #%s - Cancellation Denied', $this->order->id);
                $template = 'cancellation_denied';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                $data['detailUrl'] = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['order/index', '#' => $this->order->id], true);
                break;
        }
        
        $this->order->log(sprintf("admin notification mail %s %s to %s", $this->key, $template, $toEmail));

        $message = $mailer->compose($template, array_merge([
            'order' => $this->order,
            'notification' => $this,
            'user' => $user
        ], $data));

        $message->setFrom($fromEmail);
        $message->setTo($toEmail);
        $message->setSubject($subject);
        $message->send($mailer);
    }
}