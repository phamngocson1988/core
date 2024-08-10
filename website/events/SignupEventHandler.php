<?php
namespace website\events;

use Yii;
use yii\base\Event;
use yii\base\Model;
use website\models\User;
use website\models\Affiliate;
use website\models\UserRefer;
use website\models\UserWallet;
use website\components\notifications\AccountNotification;

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
        $invitor = Affiliate::findOne(['code' => $form->affiliate]);
        if (!$invitor) return;
        if (!$invitor->isEnable()) return;
        $user = $event->user;
        if (!$user) return;
        // Update invited user
        $user->affiliated_with = $invitor->user_id;
        $user->save();
    }

    public static function notifyStaff(AfterSignupEvent $event) 
    {
        $adminIds = Yii::$app->authManager->getUserIdsByRole('admin');
        $salerIds = Yii::$app->authManager->getUserIdsByRole('saler');
        $userIds = array_unique(array_merge($adminIds, $salerIds));
        $form = $event->sender;
        $account = $event->user;

        foreach ($userIds as $userId) {
	        AccountNotification::create(AccountNotification::NOTIFY_STAFF_NEW_ACCOUNT, [
	            'account' => $account,
	            'userId' => $userId
	        ])->send();
    	}
    }

    public static function welcome(AfterSignupEvent $event) 
    {
        Yii::$app->queue->push(new \website\queue\SignupEmail(['user' => $event->user]));
    }
}