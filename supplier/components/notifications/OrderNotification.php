<?php 
namespace supplier\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;
use supplier\models\User;

class OrderNotification extends Notification
{
    const NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE = 'NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE';
    const NOTIFY_CUSTOMER_COMPLETE_ORDER = 'NOTIFY_CUSTOMER_COMPLETE_ORDER';

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
            ],
            'email' => [
                self::NOTIFY_CUSTOMER_NEW_ORDER_MESSAGE,
                self::NOTIFY_CUSTOMER_COMPLETE_ORDER,
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

        $fromEmail = $kinggemsMail;
        $mailer = $kinggemsMailer;
        $toEmail = $user->email;
        $subject = '';
        $template = '';
        $data = [];

        switch($this->key) {
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

        }
        
        $message = $mailer->compose($template, array_merge([
            'order' => $this->order,
            'notification' => $this,
        ], $data));

        $message->setFrom($fromEmail);
        $message->setTo($toEmail);
        $message->setSubject($subject);
        $message->send($mailer);
    }
}