<?php 
namespace frontend\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;
use frontend\models\User;

class ReviewNotification extends Notification
{
    const NOTIFY_USER_OPERATOR_RESPONSE = 'NOTIFY_USER_OPERATOR_RESPONSE';
    const NOTIFY_USER_NEW_REVIEW = 'NOTIFY_USER_NEW_REVIEW'; // notification and mail

    protected $_user;

    protected getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->userId);
        }
        return $this->_user;
    }
    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key){
            case self::NOTIFY_USER_OPERATOR_RESPONSE:
            case self::NOTIFY_USER_NEW_REVIEW:
                return sprintf("Chờ phản hồi");
        }
    }

    public function getDescription()
    {
        switch($this->key){
            case self::NOTIFY_USER_OPERATOR_RESPONSE:
            case self::NOTIFY_USER_NEW_REVIEW:
                return sprintf("Chờ phản hồi");
        }
    }

    public function getIcon()
    {
        switch($this->key){
            case self::NOTIFY_USER_OPERATOR_RESPONSE:
            case self::NOTIFY_USER_NEW_REVIEW:
                return 'https://kinggems.us/images/logo_icon.png';

        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        switch($this->key){
            case self::NOTIFY_USER_OPERATOR_RESPONSE:
            case self::NOTIFY_USER_NEW_REVIEW:
                return ['order/edit', 'id' => '#'];
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
        $user = $this->getUser();
        $settings = $user->getSettings();
        return [
            'screen' => [
                self::NOTIFY_USER_OPERATOR_RESPONSE,
                self::NOTIFY_USER_NEW_REVIEW,
            ],
            'email' => [
                self::NOTIFY_USER_OPERATOR_RESPONSE,
                self::NOTIFY_USER_NEW_REVIEW,
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
        $user = $this->getUser();
        Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);
        Yii::$app->urlManagerSupplier->setHostInfo(Yii::$app->params['supplier_url']);

        $fromEmail = $kinggemsMail;
        $mailer = $kinggemsMailer;
        $toEmail = $user->email;
        $subject = '';
        $template = '';
        $data = [];

        switch($this->key) {
            self::NOTIFY_USER_OPERATOR_RESPONSE,
                self::NOTIFY_USER_NEW_REVIEW,
                $subject = sprintf('King Gems - #%s - Completed', '#');
                $template = 'cancellation_denied';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                $data['detailUrl'] = Yii::$app->urlManagerFrontend->createAbsoluteUrl(['user/detail', 'id' => '#'], true);
                break;

        }
        
        $message = $mailer->compose($template, array_merge([
            'notification' => $this,
        ], $data));

        $message->setFrom($fromEmail);
        $message->setTo($toEmail);
        $message->setSubject($subject);
        $message->send($mailer);
    }
}