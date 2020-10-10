<?php 
namespace frontend\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use frontend\models\User;

class ComplainNotification extends Notification
{
    public $complain;

    const COMPLAIN_NOT_PUBLISHED = 'COMPLAIN_NOT_PUBLISHED';
    const COMPLAIN_PUBLISHED = 'COMPLAIN_PUBLISHED';
    const OPERATOR_RESPONSE = 'OPERATOR_RESPONSE';
    const COMPLAIN_REJECTED = 'COMPLAIN_REJECTED';
    const COMPLAIN_RESOLVED = 'COMPLAIN_RESOLVED';
    const NEW_RESPONSE_COMPLAIN = 'NEW_RESPONSE_COMPLAIN';
    const NEW_COMPLAIN_PUBLISHED = 'NEW_COMPLAIN_PUBLISHED';
    protected $_user;

    protected function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->userId);
        }
        return $this->_user;
    }

    public static function settingList()
    {
        return [
            [
                'title' => 'Complaint not published',
                'key' => self::COMPLAIN_NOT_PUBLISHED,
                'platform' => ['email', 'screen']
            ],
            [
                'title' => 'Complaint published',
                'key' => self::COMPLAIN_PUBLISHED,
                'platform' => ['email', 'screen']
            ],
            [
                'title' => 'Operator’s response',
                'key' => self::OPERATOR_RESPONSE,
                'platform' => ['email', 'screen']
            ],
            [
                'title' => 'Complaint rejected',
                'key' => self::COMPLAIN_REJECTED,
                'platform' => ['email', 'screen']
            ],
            [
                'title' => 'Complaint resolved',
                'key' => self::COMPLAIN_RESOLVED,
                'platform' => ['email', 'screen']
            ],
            [
                'title' => 'New response to complaint',
                'key' => self::NEW_RESPONSE_COMPLAIN,
                'platform' => ['email', 'screen']
            ],
            [
                'title' => 'New complaint published',
                'key' => self::NEW_COMPLAIN_PUBLISHED,
                'platform' => ['email', 'screen']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key){
            case self::COMPLAIN_NOT_PUBLISHED:
                return sprintf("Complaint not published");
            case self::COMPLAIN_PUBLISHED:
                return sprintf("Complaint published");
            case self::OPERATOR_RESPONSE:
                return sprintf("Operator’s response");
            case self::COMPLAIN_REJECTED:
                return sprintf("Complaint rejected");
            case self::COMPLAIN_RESOLVED:
                return sprintf("Complaint resolved");
            case self::NEW_RESPONSE_COMPLAIN:
                return sprintf("New response to complaint");
            case self::NEW_COMPLAIN_PUBLISHED:
                return sprintf("New complaint published");
        }
    }

    public function getDescription()
    {
        $complain = $this->complain;
        switch($this->key){
            case self::COMPLAIN_NOT_PUBLISHED:
                return sprintf("Complaint not published");
            case self::COMPLAIN_PUBLISHED:
                return sprintf("Complaint published");
            case self::OPERATOR_RESPONSE:
                return sprintf("Operator’s response");
            case self::COMPLAIN_REJECTED:
                return sprintf("Complaint rejected");
            case self::COMPLAIN_RESOLVED:
                return sprintf("Complaint resolved");
            case self::NEW_RESPONSE_COMPLAIN:
                return sprintf("New response to complaint");
            case self::NEW_COMPLAIN_PUBLISHED:
                return sprintf("New complaint published");
        }
    }

    public function getIcon()
    {
        $operator = $this->complain->operator;
        $user = $this->getUser();
        switch($this->key){
            case self::COMPLAIN_NOT_PUBLISHED:
            case self::COMPLAIN_PUBLISHED:
            case self::COMPLAIN_REJECTED:
            case self::COMPLAIN_RESOLVED:
            case self::NEW_RESPONSE_COMPLAIN:
            case self::OPERATOR_RESPONSE:
                return $operator->getImageUrl('100x100');
            case self::NEW_COMPLAIN_PUBLISHED:
                return $user->getAvatarUrl('100x100');
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute()
    {
        $complain = $this->complain;
        switch($this->key){
            case self::COMPLAIN_NOT_PUBLISHED:
            case self::COMPLAIN_PUBLISHED:
            case self::COMPLAIN_REJECTED:
            case self::COMPLAIN_RESOLVED:
            case self::NEW_RESPONSE_COMPLAIN:
            case self::NEW_COMPLAIN_PUBLISHED:
            case self::OPERATOR_RESPONSE:
                return Url::to(['complain/view', 'id' => $complain->id, 'slug' => $complain->slug]);
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
                self::COMPLAIN_NOT_PUBLISHED,
                self::COMPLAIN_PUBLISHED,
                self::COMPLAIN_REJECTED,
                self::COMPLAIN_RESOLVED,
                self::NEW_RESPONSE_COMPLAIN,
                self::NEW_COMPLAIN_PUBLISHED,
                self::OPERATOR_RESPONSE,
            ],
            'email' => [
                self::COMPLAIN_NOT_PUBLISHED,
                self::COMPLAIN_PUBLISHED,
                self::COMPLAIN_REJECTED,
                self::COMPLAIN_RESOLVED,
                self::NEW_RESPONSE_COMPLAIN,
                self::NEW_COMPLAIN_PUBLISHED,
                self::OPERATOR_RESPONSE,
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
        // $customerServiceMail = 'phamngocson1988@gmail.com';
        // $customerServiceMailer = Yii::$app->mailer;
        // $operator = $this->review->operator;
        // $user = $this->getUser();
        // $toEmail = $user->email;
        // $subject = '';
        // $template = '';
        // $data = [];

        // switch($this->key) {
        //     case self::MAIL_OPERATOR_RESPONSE: {
        //         $subject = sprintf('BW2020 - %s have just response your review', $operator->name);
        //         $template = self::MAIL_OPERATOR_RESPONSE;
        //         $fromEmail = $customerServiceMail;
        //         $mailer = $customerServiceMailer;
        //         $data['operatorUrl'] = Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug], true);
        //         $data['review'] = $this->review;
        //         break;
        //     }

        //     case self::MAIL_NEW_PLAYER_REVIEW: {
        //         $subject = sprintf('BW2020 - %s have just received new review', $operator->name);
        //         $template = self::MAIL_NEW_PLAYER_REVIEW;
        //         $fromEmail = $customerServiceMail;
        //         $mailer = $customerServiceMailer;
        //         $data['operatorUrl'] = Url::to(['operator/view', 'id' => $operator->id, 'slug' => $operator->slug], true);
        //         $data['review'] = $this->review;
        //         break;
        //     }
        // }
        
        // $message = $mailer->compose($template, array_merge([
        //     'notification' => $this,
        // ], $data));

        // $message->setFrom($fromEmail);
        // $message->setTo($toEmail);
        // $message->setSubject($subject);
        // $message->send($mailer);
    }
}