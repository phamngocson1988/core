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

    public function walletTotal($type = null, $from = null, $to = null)
    {
        $user = $this->owner; // Supplier
        $command = SupplierWallet::find()->where(['supplier_id' => $user->user_id]);
        if ($type) {
            $command->andWhere(['type' => $type]);
        }
        if ($from && $to) {
            $command->andWhere(['between', 'created_at', $from, $to]);
        }
        return $command->sum('amount');
    }

    public function walletTotalInput($from = null, $to = null)
    {
        return $this->walletTotal(SupplierWallet::TYPE_INPUT, $from, $to);
    }

    public function walletTotalOutput($from = null, $to = null)
    {
        return $this->walletTotal(SupplierWallet::TYPE_OUTPUT, $from, $to);
    }
}
