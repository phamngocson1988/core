<?php
namespace website\queue;

use Yii;
use yii\base\BaseObject;

class SignupEmail extends BaseObject implements \yii\queue\JobInterface
{
    public $user;
    
    public function execute($queue)
    {
        $user = $this->user;
        $admin = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
        $siteName = 'Kinggems Us';
        return Yii::$app->mailer->compose('welcome_newcomer', ['user' => $user])
        ->setTo($user->email)
        ->setFrom([$admin => $siteName])
        ->setSubject('KingGems- Welcome!!!')
        ->setTextBody("Welcome to Kinggems")
        ->send();
    }
}