<?php
namespace frontend\events;

use Yii;
use yii\base\Event;
use yii\base\Model;
use frontend\models\User;
use frontend\models\UserAffiliate;
use frontend\models\UserRefer;
use frontend\models\UserWallet;

class SignupEventHandler extends Model
{
    public static function referCheckingEvent(AfterSignupEvent $event) 
    {
        $form = $event->sender;
        if (!$form->refer) return;
        $invitor = User::findOne(['refer_code' => $form->refer]);
        if (!$invitor) return;
        $user = $event->user;
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
            $referModel->note = 'Created';
        }
        $referModel->status = UserRefer::STATUS_CREATED;
        $referModel->save();
    }

    public static function affiliateCheckingEvent(AfterSignupEvent $event) 
    {
        $form = $event->sender;
        if (!$form->affiliate) return;
        $invitor = UserAffiliate::findOne(['code' => $form->affiliate]);
        if (!$invitor) return;
        if (!$invitor->isEnable()) return;
        $user = $event->user;
        if (!$user) return;
        // Update invited user
        $user->affiliated_with = $invitor->user_id;
        $user->save();
    }

    public static function salerCheckingEvent(AfterSignupEvent $event) 
    {
        $form = $event->sender;
        if (!$form->saler_code) return;
        $invitor = User::findOne(['saler_code' => $form->saler_code]);
        if (!$invitor) return;
        $user = $event->user;
        if (!$user) return;
        // Update invited user
        $user->saler_id = $invitor->id;
        $user->save();
        Yii::$app->session->remove('saler_code');
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
        $admin = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
        $siteName = Yii::$app->name;
        $email = Yii::$app->mailer->compose('welcome_newcomer', [
            'user' => $user,
            'contact_email' => $admin
        ])
        ->setTo($user->email)
        ->setFrom([$admin => $siteName])
        ->setSubject('Registration Confirmation')
        ->setTextBody("Welcome to Kinggems")
        ->send();
    }

    public static function signonBonus($event) 
    {
        $setting = Yii::$app->settings;
        if (!$setting->get('WelcomeBonusForm', 'status')) return;
        if ($setting->get('WelcomeBonusForm', 'content')) {

        }        
        if ($setting->get('WelcomeBonusForm', 'value')) {
            $user = $event->sender;
            // $wallet = new UserWallet();
            // $wallet->coin = (int)$setting->get('WelcomeBonusForm', 'value', 0);
            // $wallet->balance = $wallet->coin;
            // $wallet->type = UserWallet::TYPE_INPUT;
            // $wallet->description = "Signon Bonus";
            // $wallet->created_by = $user->id;
            // $wallet->user_id = $user->id;
            // $wallet->status = UserWallet::STATUS_COMPLETED;
            // $wallet->payment_at = date('Y-m-d H:i:s');
            // $wallet->save();
            $user->topup((int)$setting->get('WelcomeBonusForm', 'value', 0), null, 'Signon Bonus');
        }        
    }
}