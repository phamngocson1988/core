<?php 
namespace frontend\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;
use frontend\models\User;

class ComplainNotification extends Notification
{
    public $review;

    const BELL_COMPLAIN_NOT_PUBLISHED = 'BELL_COMPLAIN_NOT_PUBLISHED';
    const MAIL_COMPLAIN_NOT_PUBLISHED = 'MAIL_COMPLAIN_NOT_PUBLISHED';
    const BELL_COMPLAIN_PUBLISHED = 'BELL_COMPLAIN_PUBLISHED';
    const MAIL_COMPLAIN_PUBLISHED = 'MAIL_COMPLAIN_PUBLISHED';
    const BELL_OPERATOR_REPLY = 'BELL_OPERATOR_REPLY';
    const MAIL_OPERATOR_REPLY = 'MAIL_OPERATOR_REPLY';
    const BELL_COMPLAIN_REJECTED = 'BELL_COMPLAIN_REJECTED';
    const MAIL_COMPLAIN_REJECTED = 'MAIL_COMPLAIN_REJECTED';
    const BELL_COMPLAIN_RESOLVED = 'BELL_COMPLAIN_RESOLVED';
    const MAIL_COMPLAIN_RESOLVED = 'MAIL_COMPLAIN_RESOLVED';
    const BELL_NEW_RESPONSE_COMPLAIN = 'BELL_NEW_RESPONSE_COMPLAIN';
    const MAIL_NEW_RESPONSE_COMPLAIN = 'MAIL_NEW_RESPONSE_COMPLAIN';
    const BELL_NEW_COMPLAIN_PUBLISHED = 'BELL_NEW_COMPLAIN_PUBLISHED';
    const MAIL_NEW_COMPLAIN_PUBLISHED = 'MAIL_NEW_COMPLAIN_PUBLISHED';
    protected $_user;

    protected function getUser()
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
            case self::BELL_OPERATOR_RESPONSE:
                return sprintf("Operator Response");
        }
    }

    public function getDescription()
    {
        $operator = $this->review->operator;
        switch($this->key){
            case self::BELL_OPERATOR_RESPONSE:
                return sprintf("You have received a review response from %s", $Operator->name);
        }
    }

    public function getIcon()
    {
        $operator = $this->review->operator;
        switch($this->key){
            case self::BELL_OPERATOR_RESPONSE:
                return $operator->getImageUrl('100x100');

        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute()
    {
        $operator = $this->review->operator;
        switch($this->key){
            case self::BELL_OPERATOR_RESPONSE:
                return ['operator/view', 'id' => $operator->id, 'slug' => $operator->slug];
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
                self::BELL_OPERATOR_RESPONSE,
            ],
            'email' => [
                self::MAIL_OPERATOR_RESPONSE,
                self::MAIL_NEW_PLAYER_REVIEW,
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
        $customerServiceMail = 'phamngocson1988@gmail.com';
        $customerServiceMailer = Yii::$app->mailer;
        $operator = $this->review->operator;
        $user = $this->getUser();
        $toEmail = $user->email;
        $subject = '';
        $template = '';
        $data = [];

        switch($this->key) {
            case self::MAIL_OPERATOR_RESPONSE: {
                $subject = sprintf('BW2020 - %s have just response your review', $operator->name);
                $template = self::MAIL_OPERATOR_RESPONSE;
                $fromEmail = $customerServiceMail;
                $mailer = $customerServiceMailer;
                $data['operatorUrl'] = Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug], true);
                $data['review'] = $this->review;
                break;
            }

            case self::MAIL_NEW_PLAYER_REVIEW: {
                $subject = sprintf('BW2020 - %s have just received new review', $operator->name);
                $template = self::MAIL_NEW_PLAYER_REVIEW;
                $fromEmail = $customerServiceMail;
                $mailer = $customerServiceMailer;
                $data['operatorUrl'] = Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug], true);
                $data['review'] = $this->review;
                break;
            }
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