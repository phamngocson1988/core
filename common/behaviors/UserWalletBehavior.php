<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\UserWallet;

class UserWalletBehavior extends AttributeBehavior
{
    public function withdraw($amount, $refId = null, $description = '')
    {
        $user = $this->owner;
        $wallet = new UserWallet();
        $wallet->coin = (-1) * $amount;
        $wallet->balance = $user->getWalletAmount() + $wallet->coin;
        $wallet->type = UserWallet::TYPE_OUTPUT;
        $wallet->description = $description;
        $wallet->ref_name = '';
        $wallet->ref_key = $refId;
        $wallet->created_by = $user->id;
        $wallet->user_id = $user->id;
        $wallet->status = UserWallet::STATUS_COMPLETED;
        $wallet->payment_at = date('Y-m-d H:i:s');
        $wallet->save();
    }

    public function topup($amount, $refId = null, $description = '')
    {
        $user = $this->owner;
        $wallet = new UserWallet();
        $wallet->coin = $amount;
        $wallet->balance = $user->getWalletAmount() + $wallet->coin;
        $wallet->type = UserWallet::TYPE_INPUT;
        $wallet->description = $description;
        $wallet->ref_name = '';
        $wallet->ref_key = $refId;
        $wallet->created_by = $user->id;
        $wallet->user_id = $user->id;
        $wallet->status = UserWallet::STATUS_COMPLETED;
        $wallet->payment_at = date('Y-m-d H:i:s');
        $wallet->save();
    }
}
