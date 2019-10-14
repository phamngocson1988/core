<?php

namespace frontend\components\verification\twilio;

use Yii;
use yii\base\Model;

class Sms extends Model
{
    public $testing_mode = false;

    protected function createCode()
    {
        $session = Yii::$app->session;
        $pin = mt_rand(1000, 9999);
        $session->set('pin', $pin);
        return $pin;
    }

    public function send($phone, $content, $params = [])
    {
        $params['{pin}'] = $this->createCode();
        $newContent = str_replace(array_keys($params), array_values($params), $content);
        $service = Yii::$app->sms;
        // Testing: 
        // if ($this->testing_mode) $phone = "+84986803325";

        return $service->compose()
        ->setTo($phone)
        ->setMessage($newContent)
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