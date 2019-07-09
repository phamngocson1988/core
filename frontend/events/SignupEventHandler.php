<?php
namespace frontend\events;

use Yii;
use yii\base\Event;
use yii\base\Model;
use frontend\models\UserRefer;

class SignupEventHandler extends Model
{
    public static function referCheckingEvent(AfterSignupEvent $event) 
    {
        $form = $event->sender;
        if (!$form->refer) return;
        $invitor = User::findOne(['refer_code' => $form->refer]);
        if (!$invitor) return;
        $user = $form->user;
        if (!$user) return;
        // Update invited user
        $user->referred_by = $invitor->id;
        $user->save();
        // Process in user refer table
        $referModel = UserRefer::findOne(['user_id' => $invitor->id, 'email' => $user->email]);
        if (!$referModel) {
            $referModel = new UserRefer();
            $referModel->user_id = $invitor->id;
            $referModel->name = $user->name;
            $referModel->email = $user->email;
            $referModel->status = UserRefer::STATUS_CREATED;
            $referModel->note = 'Created not from email refer';
            $referModel->save();
        } else {
            $referModel->status = UserRefer::STATUS_CREATED;
            $referModel->save();
        }
    }

    public static function affiliateCheckingEvent(AfterSignupEvent $event) 
    {
        $form = $event->sender;
        if (!$form->affiliate) return;
        $invitor = User::findOne(['affiliate_code' => $form->affiliate]);
        if (!$invitor) return;
        $user = $form->user;
        if (!$user) return;
        // Update invited user
        $user->affiliated_with = $invitor->id;
        $user->save();
    }

    public static function referApplyingEvent($event) 
    {
        $user = $event->sender;
        if (!$user) return;
        if (!$user->referred_by) return;
        // Process in user refer table
        $referModel = UserRefer::findOne([
            'user_id' => $user->referred_by, 
            'email' => $user->email,
            'status' => UserRefer::STATUS_CREATED
        ]);
        if ($referModel) {
            $referModel->status = UserRefer::STATUS_ACTIVATED;
            $referModel->save();
        }
    }

    public static function notifyWelcomeEmail($event) 
    {
        $user = $event->sender;
        if (!$user) return;
        $admin = Yii::$app->params['email_admin'];
        $siteName = Yii::$app->name;
        $email = Yii::$app->mailer->compose('welcome_newcomer', [
            'user' => $user
        ])
        ->setTo($user->email)
        ->setFrom([$admin => $siteName])
        ->setSubject('Registration Confirmation')
        ->setTextBody("Welcome to Kinggems")
        ->send();
    }
}

// ==== Declare events =====
class AfterSignupEvent extends Event
{
    /** 
     * The user which has just created
     * @var instanceof \common\models\User
     */
    public $user;
}