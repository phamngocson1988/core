<?php 
namespace frontend\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;
use frontend\models\User;

class ReviewNotification extends Notification
{
    public $review;

    const OPERATOR_RESPONSE = 'OPERATOR_RESPONSE';
    const NEW_PLAYER_REVIEW = 'NEW_PLAYER_REVIEW';
    protected $_user;

    public static function settingList()
    {
        return [
            [
                'title' => 'Operator’s response',
                'key' => self::OPERATOR_RESPONSE,
                'platform' => ['email', 'onsite']
            ],
            [
                'title' => 'New player review',
                'key' => self::NEW_PLAYER_REVIEW,
                'platform' => ['email']
            ]
        ];
    }

    public function getUser()
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
            case self::OPERATOR_RESPONSE:
                return sprintf("Operator Response");
        }
    }

    public function getDescription()
    {
        $operator = $this->review->operator;
        switch($this->key){
            case self::OPERATOR_RESPONSE:
                return sprintf("You have received a review response from %s", $Operator->name);
        }
    }

    public function getIcon()
    {
        $operator = $this->review->operator;
        switch($this->key){
            case self::OPERATOR_RESPONSE:
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
            case self::OPERATOR_RESPONSE:
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
                self::OPERATOR_RESPONSE,
            ],
            'email' => [
                self::NEW_PLAYER_REVIEW,
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
            case self::OPERATOR_RESPONSE: {
                $subject = sprintf('BW2020 - %s have just response your review', $operator->name);
                $fromEmail = $customerServiceMail;
                $mailer = $customerServiceMailer;
                $data['operatorUrl'] = Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug], true);
                $data['review'] = $this->review;
                break;
            }

            case self::NEW_PLAYER_REVIEW: {
                $subject = sprintf('BW2020 - %s have just received new review', $operator->name);
                $template = self::NEW_PLAYER_REVIEW;
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