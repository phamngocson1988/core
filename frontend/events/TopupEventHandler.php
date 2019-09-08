<?php
namespace frontend\events;

use Yii;
use yii\base\Event;
use yii\base\Model;
use frontend\models\UserRefer;
use frontend\models\PaymentTransaction;

class TopupEventHandler extends Model
{
    public static function applyReferGift($event) 
    {
        $status = Yii::$app->settings->get('ReferProgramForm', 'status', 0);
        if (!$status) return;
        $min_total_price = Yii::$app->settings->get('ReferProgramForm', 'min_total_price', 50);
        $gift_value = Yii::$app->settings->get('ReferProgramForm', 'gift_value', 5);
        $model = $event->sender; //transaction
        // refer gift just be applied only if the transaction is completed
        if ($model->status != PaymentTransaction::STATUS_COMPLETED) return;
        // refer gift just be applied for user who was invited by another account
        $user = $model->user;
        if (!$user->referred_by) return;
        // refer gift just be applied for the first transaction
        $command = $user->getTransactions();
        if ($command->count() > 1) return; 
        
        // Apply
        $refer = UserRefer::findOne([
            'user_id' => $user->referred_by, 
            'email' => $user->email,
            'status' => UserRefer::STATUS_ACTIVATED
        ]);
        if (!$refer) return;
        // refer gift just be applied when transaction's amount is bigger than 50

        if ($model->total_price < $min_total_price) {
            $refer->status = UserRefer::STATUS_INVALID;
            $refer->note = 'The first transaction has amount litte than 50';
        }
        elseif (!$refer->checkExpired()) {
            $refer->status = UserRefer::STATUS_INVALID;
            $refer->note = 'The gift is expired';
        } else {
            $refer->status = UserRefer::STATUS_PAYMENT;
            $refer->payment_at = date('Y-m-d H:i:s');
            $refer->note = "You have gifted $gift_value coins.";
        }
        $refer->save();
        return;
    }

    public static function welcomeBonus($event) 
    {
        $setting = Yii::$app->settings;
        // Apply when this program is active
        if (!$setting->get('WelcomeBonusForm', 'status')) return;

        // Apply for the first topup
        $transaction = $event->sender; //transaction
        $user = $transaction->user;
        $command = $user->getTransactions();
        if ($command->count() > 1) return; 

        // Apply bonus
        $value = $setting->get('WelcomeBonusForm', 'value', 0);
        if ($value) {
            $user->topup($value, null, 'Signon Bonus');
        }        
    }    

    public static function sendNotificationEmail($event)
    {
        $wallet = $event->sender; //wallet
        $user = $wallet->user;
        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
        if ($adminEmail) {
            $email = Yii::$app->mailer->compose('order_kingcoin', ['wallet' => $wallet])
            ->setTo($user->email)
            ->setFrom([$adminEmail => Yii::$app->name . ' Administrator'])
            ->setSubject(sprintf("SUCCESSFUL DEPOSIT - %s", $wallet->id))
            ->setTextBody("Thanks for your deposit")
            ->send();
        }

    }
}