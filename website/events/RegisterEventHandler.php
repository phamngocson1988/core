<?php
namespace frontend\events;

use Yii;
use yii\base\Event;
use yii\base\Model;
use frontend\models\User;
use frontend\models\UserAffiliate;
use frontend\models\UserRefer;
use frontend\models\UserWallet;

class RegisterEventHandler extends Model
{
    /**
     * @param AfterSignupEvent $event
     * @see AfterSignupEvent
     */
    public static function referCheckingEvent($event) 
    {
        /** @var $form RegisterForm **/
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

    /**
     * @param AfterSignupEvent $event
     * @see AfterSignupEvent
     */
    public static function affiliateCheckingEvent($event) 
    {
        /** @var $form RegisterForm **/
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

    /**
     * @param AfterSignupEvent $event
     * @see AfterSignupEvent
     */
    public static function salerCheckingEvent($event) 
    {
        /** @var $form RegisterForm **/
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

    /**
     * @param AfterSignupEvent $event
     * @see AfterSignupEvent
     */
    public static function assignRole($event)
    {
        $user = $event->user;
        $auth = Yii::$app->authManager;
        $customer = $auth->getRole('customer');
        $auth->assign($customer, $user->id);
    }

    /**
     * @param AfterSignupEvent $event
     * @see AfterSignupEvent
     */
    public static function sendActivationEmail($event) 
    {
        $user = $event->user;
        if (!$user) return;
        $admin = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
        $siteName = Yii::$app->name;
        $email = Yii::$app->mailer->compose('signup_mail', [
            'user' => $user,
        ])
        ->setTo($user->email)
        ->setFrom([$admin => $siteName])
        ->setSubject('Verify your email')
        ->setTextBody("Verify your email")
        ->send();
    }

    /**
     * @param AfterSignupEvent $event
     * @see AfterSignupEvent
     */
    public static function referApplyingEvent($event) 
    {
        $user = $event->user;
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

    /**
     * @param AfterSignupEvent $event
     * @see AfterSignupEvent
     */
    public static function notifyWelcomeEmail($event) 
    {
        $user = $event->user;
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

    /**
     * @param AfterSignupEvent $event
     * @see AfterSignupEvent
     */
    public static function signonBonus($event) 
    {
        $setting = Yii::$app->settings;
        if (!$setting->get('WelcomeBonusForm', 'status')) return;
        if ($setting->get('WelcomeBonusForm', 'value')) {
            $user = $event->user;
            $user->topup((int)$setting->get('WelcomeBonusForm', 'value', 0), null, 'WELCOME GIFT', UserWallet::STATUS_WAITING);
        }        
    }    
}