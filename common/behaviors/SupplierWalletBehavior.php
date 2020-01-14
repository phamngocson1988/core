<?php
namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use common\models\SupplierWallet;

class SupplierWalletBehavior extends AttributeBehavior
{
    public function withdraw($amount, $description = '')
    {
        $user = $this->owner; // Supplier
        $wallet = new SupplierWallet();
        $wallet->amount = (-1) * $amount;
        $wallet->type = SupplierWallet::TYPE_OUTPUT;
        $wallet->description = $description;
        $wallet->supplier_id = $user->user_id;
        $wallet->status = SupplierWallet::STATUS_COMPLETED;
        $wallet->save();
    }

    public function topup($amount, $description = '', $status = SupplierWallet::STATUS_COMPLETED)
    {
        $user = $this->owner; // Supplier
        $wallet = new SupplierWallet();
        $wallet->amount = $amount;
        $wallet->type = SupplierWallet::TYPE_INPUT;
        $wallet->description = $description;
        $wallet->supplier_id = $user->user_id;
        $wallet->status = $status;
        $wallet->save();
    }
}
