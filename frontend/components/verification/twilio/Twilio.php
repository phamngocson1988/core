<?php

namespace frontend\components\verification\twilio;

use Yii;

class Twilio
{
    protected function createCode()
    {
        $session = Yii::$app->session;
        $pin = mt_rand(1000, 9999);
        $session->set('pin', $pin);
        return $pin;
    }

    public function send()
    {
        $service = Yii::createObject([
            'class' => 'wadeshuler\sms\twilio\Sms',
            'useFileTransport' => false,
            'messageConfig' => [
                'from' => '+13345181969',
            ],
            'sid' => 'AC4fe59143825d5f20f27fb1b0fd65f468',
            'token' => '97f1c1148dae6321de52efefc9d86bbb',
            'fileTransportCallback' => function ($service, $message) {
                return 'testsms.txt';
            }
        ]);
        $pin = $this->createCode();
        $service->compose()
        ->setTo('+84907877310')
        ->setMessage("Your validation code is: $pin")
        ->send();
    }

    public function verify($pin)
    {
        $session = Yii::$app->session;
        $key = $session->get('pin');
        if ($pin == $key) {
            $session->remove('pin');
            return true;
        }
        return false;
    }
}