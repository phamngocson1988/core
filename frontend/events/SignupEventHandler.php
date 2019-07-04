<?php
namespace frontend\events;

use yii\base\Event;
use yii\base\Model;
use frontend\models\UserRefer;

class SignupEventHandler extends Model
{
    public static function afterSignupEvent(AfterSignupEvent $event) 
    {
        $form = $event->sender;
        if (!$form->refer) return;
        $invitor = User::findOne(['affiliate_code' => $form->refer]);
        if (!$invitor) return;
        $user = $form->user;
        if (!$user) return;
        // Update invited user
        $user->invited_by = $invitor->id;
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

    public static function afterActiveEvent($event) 
    {
        $user = $event->sender;
        if (!$user) return;
        if (!$user->invited_by) return;
        // Process in user refer table
        $referModel = UserRefer::findOne([
            'user_id' => $user->invited_by, 
            'email' => $user->email,
            'status' => UserRefer::STATUS_CREATED
        ]);
        if ($referModel) {
            $referModel->status = UserRefer::STATUS_ACTIVATED;
            $referModel->save();
        }
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