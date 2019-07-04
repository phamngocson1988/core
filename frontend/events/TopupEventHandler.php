<?php
namespace frontend\events;

use yii\base\Event;
use yii\base\Model;
use frontend\models\UserRefer;
use frontend\models\UserWallet;

class TopupEventHandler extends Model
{
    public static function applyReferGift($event) 
    {
        $model = $event->sender; //wallet
        // refer gift just be applied for top (input) transaction
        if ($model->type != UserWallet::TYPE_INPUT) return;
        // refer gift just be applied only if the transaction is completed
        if ($model->status != UserWallet::STATUS_COMPLETED) return;
        // refer gift just be applied for user who was invited by another account
        $user = Yii::$app->user->getIdentity();
        if (!$user->invited_by) return;
        // refer gift just be applied for the first transaction
        $command = UserWallet::find();
        $command->where(['user_id' => $user->id]);
        $command->andWhere(['type' => UserWallet::TYPE_INPUT]);
        $command->andWhere(['status' => UserWallet::STATUS_COMPLETED]);
        if ($command->count() > 1) return;
        
        // Apply
        $refer = UserRefer::find([
            'user_id' => $user->invited_by, 
            'email' => $user->email,
            'status' => UserRefer::STATUS_ACTIVATED
        ]);
        if (!$refer) return;
        // refer gift just be applied when transaction's amount is bigger than 50
        if ($model->coin < 50) {
            $refer->status = UserRefer::STATUS_INVALID;
            $refer->note = 'The first transaction has amount litte than 50';
        }
        elseif (!$refer->checkExpired()) {
            $refer->status = UserRefer::STATUS_INVALID;
            $refer->note = 'The gift is expired';
        } else {
            $refer->status = UserRefer::STATUS_PAYMENT;
            $refer->note = 'You have gifted 10 coin.';
        }
        $refer->save();
        return;
    }
}