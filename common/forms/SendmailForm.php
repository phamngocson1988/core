<?php
namespace common\forms;

use Yii;
use yii\base\Model;

/**
 * SendmailForm
 */
class SendmailForm extends Model
{
    public $subject;
    public $body;
    public $params;
    /** @var string common\mail\[template.php] */
    public $template;

    protected $fromEmail;
    protected $fromName;
    protected $mailer;

    public function send($email) 
    {
        $fromEmail = $this->getFromEmail();
        $fromName = $this->getFromName();
        if (!$fromEmail || !$email) return false;
        
        $mailer = $this->getMailer();
        if ($this->template) {
            $mail = $mailer->compose($this->template, $this->params);
        } else {
            $mail = $mailer->compose();
        }
        return $mail
            ->setTo($email)
            ->setFrom([$fromEmail => $fromName])
            ->setSubject($this->subject)
            ->setTextBody($this->body)
            ->send();
    }

    public function getFromEmail()
    {
        if (!$this->fromEmail) {
            $settings = Yii::$app->settings;
            return $settings->get('ApplicationSettingForm', 'admin_email', null);
        }
    }

    public function getFromName()
    {
        if (!$this->fromName) {
            return Yii::$app->name;
        }
    }

    public function setFromEmail($email, $name) 
    {
        $this->fromEmail = $email;
        $this->fromName = $name;
    }

    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }

    public function getMailer()
    {
        if (!$this->mailer) {
            $this->mailer = Yii::$app->mailer;
        }
        return $this->mailer;
    }
}