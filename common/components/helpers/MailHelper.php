<?php
namespace common\components\helpers;
use Yii;

class MailHelper 
{
    protected $mailer;
    protected $fromEmail;
    protected $siteName;

    /**
     * Set Mailer
     */
    public function setMailer($mailer) 
    {
        $this->mailer = $mailer;
        return $this;
    }

    /**
     * Set fromEmail from setting
     */
    public function setFromEmail($name)
    {
        $this->fromEmail = Yii::$app->settings->get('ApplicationSettingForm', $name);
        return $this;
    }

    public function usingCustomerService() 
    {
        return $this->setFromEmail('customer_service_email');
    }

    public function usingSupplierService()
    {
        return $this->setFromEmail('supplier_service_email');
    }

    /**
     * Set siteName
     */
    public function setSiteName($siteName)
    {
        $this->siteName = $siteName;
        return $this;
    }

    public function usingKinggemsSiteName()
    {
        return $this->setSiteName('Kinggems');
    }

    public function usingSupplierSiteName()
    {
        return $this->setSiteName('HoangGiaNapGame');
    }

    public function send($subject, $toEmail, $template, $params) 
    {
        $this->mailer->compose($template, $params)
        ->setTo($toEmail)
        ->setFrom([$this->fromEmail => $this->siteName])
        ->setSubject($subject)
        ->setTextBody($subject)
        ->send();
        return true;
    }
}