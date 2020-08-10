<?php 
namespace backend\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;
use backend\models\User;
use backend\models\Game;

class GameNotification extends Notification
{
    const NOTIFY_NEW_PRICE = 'NOTIFY_NEW_PRICE';
    const NOTIFY_IN_STOCK = 'NOTIFY_IN_STOCK';
    const NOTIFY_NEW_PROMOTION_FOR_GAME = 'NOTIFY_NEW_PROMOTION_FOR_GAME';

    /**
     * @var \backend\models\Game the order object
     */
    public $game;

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key) {
            case self::NOTIFY_NEW_PRICE:
                return "New price";
            case self::NOTIFY_IN_STOCK:
                return "In stock";
            case self::NOTIFY_NEW_PROMOTION_FOR_GAME:
                return "New promotion";
        }
    }

    public function getDescription()
    {
        $game = $this->game;
        switch($this->key) {
            case self::NOTIFY_NEW_PRICE:
                return sprintf("Game %s price has been updated", $game->title);
            case self::NOTIFY_IN_STOCK:
                return sprintf("Game %s is in-stock now", $game->title);
            case self::NOTIFY_NEW_PROMOTION_FOR_GAME:
                return sprintf("Game %s has new promotion", $game->title);
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        $game = $this->game;
        return '';
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
                self::NOTIFY_NEW_PRICE,
                self::NOTIFY_IN_STOCK,
                self::NOTIFY_NEW_PROMOTION_FOR_GAME,
            ],
            'email' => [
                self::NOTIFY_NEW_PRICE,
                self::NOTIFY_IN_STOCK,
                self::NOTIFY_NEW_PROMOTION_FOR_GAME,
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
                $subject = sprintf('King Gems - #%s - Order Confirmed', $this->order->id);
                $template = 'order_confirmed';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                break;
            case self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE:
                $subject = sprintf('King Gems - #%s - Information Request', $this->order->id);
                $template = 'infomation_request';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                $data['detailUrl'] = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $this->order->id], true);
                break;
            case self::NOTIFY_CUSTOMER_COMPLETE_ORDER:
                $subject = sprintf('King Gems - #%s - Completed', $this->order->id);
                $template = 'completed';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                $data['detailUrl'] = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $this->order->id], true);
                break;
            case self::NOTIFY_CUSTOMER_CANCELLATION_ACCEPTED_ORDER:
                $subject = sprintf('King Gems - #%s - Cancellation Accepted', $this->order->id);
                $template = 'cancellation_accepted';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                $data['detailUrl'] = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $this->order->id], true);
                break;
            case self::NOTIFY_CUSTOMER_CANCELLATION_DENIED_ORDER:
                $subject = sprintf('King Gems - #%s - Cancellation Denied', $this->order->id);
                $template = 'cancellation_denied';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                $data['detailUrl'] = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => $this->order->id], true);
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