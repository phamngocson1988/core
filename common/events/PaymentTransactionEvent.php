<?php
namespace common\events;

use Yii;
use yii\base\Event;
use yii\base\Model;
use common\models\UserRefer;
use common\models\UserWallet;
use common\models\PaymentTransaction;

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
}