<?php
namespace console\queue;

use Yii;
use yii\base\BaseObject;
use common\models\User;

class SignupEmail extends BaseObject implements \yii\queue\JobInterface
{
    public $id;
    
    public function execute($queue)
    {
        $user = User::findOne($this->id);
        if (!$user) return;
        // Yii::warning($user, 'queue');
        $admin = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
        // Yii::warning($admin, 'queue');
        $siteName = 'Kinggems Us';
        return Yii::$app->mailer->compose('welcome_newcomer', ['user' => $user])
        ->setTo($user->email)
        ->setFrom([$admin => $siteName])
        ->setSubject('Registration Confirmation')
        ->setTextBody("Welcome to Kinggems")
        ->send();
    }
}