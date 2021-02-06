<?php
namespace common\events;

use Yii;
use yii\base\Event;
use yii\base\Model;
use common\models\UserRefer;
use common\models\UserWallet;
use common\models\PaymentTransaction;
use common\models\User;

class PaymentTransactionEvent extends Model
{
	public static function welcomeBonus($event) 
    {
        $setting = Yii::$app->settings;
        // Apply when this program is active
        if (!$setting->get('WelcomeBonusForm', 'status')) return;

        // Apply for the first topup
        $topup_value = (float)$setting->get('WelcomeBonusForm', 'topup_value');
        $transaction = $event->sender; //transaction
        if ($transaction->total_price < $topup_value) return;
        // Apply bonus
        $waiting = UserWallet::findOne(['user_id' => $transaction->user_id, 'status' => UserWallet::STATUS_WAITING]);
        if ($waiting) {
            $user = $transaction->user;
            $user->topup($waiting->coin, null, $waiting->description);
            $waiting->delete();
        }
    }    

    public static function topupUserWallet($event)
    {
        $transaction = $event->sender; //transaction
        $user = $transaction->user;
        $user->topup($transaction->total_coin, $transaction->auth_key, "Transaction #$transaction->id");
    }

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
            $invitor = User::findOne($user->referred_by);
            $invitor->topup($gift_value, $user->id, "You have gifted $gift_value coins for inviting $user->name via refer friend program");
        }
        $refer->save();
        return;
    }
}