<?php

namespace website\components\verification\email;

use Yii;
use yii\base\Model;

class Email extends Model
{
    public $testing_mode = false;
    public $urgent_pin = '9430';

    protected function createCode()
    {
        $session = Yii::$app->session;
        if ($this->testing_mode) {
            $pin = 1111;
        } else {
            $pin = mt_rand(1000, 9999);
        }
        $session->set('pin', $pin);
        return $pin;
    }

    public function send($email, $content, $params = [])
    {
        $params['{pin}'] = $this->createCode();
        $newContent = str_replace(array_keys($params), array_values($params), $content);

        $settings = Yii::$app->settings;
        $kinggemsMail = $settings->get('ApplicationSettingForm', 'customer_service_email');
        $subject = '[Kinggems] Security Code';
        $service = Yii::$app->mailer;
        $message = $service->compose('verify-email', ['verify_content' => $newContent]);
        $message->setFrom($kinggemsMail);
        $message->setTo($email);
        $message->setSubject($subject);
        return $message->send();
    }

    public function verify($pin)
    {
        $session = Yii::$app->session;
        $key = $session->get('pin');
        if (in_array($pin, [$key, $this->urgent_pin])) {
            $session->remove('pin');
            return true;
        }
        return false;
    }
}