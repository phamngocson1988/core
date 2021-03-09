<?php
namespace website\components\payment\paygate;

use Yii;
use website\models\UserWallet;
use yii\helpers\Url;
use website\models\Order;
use website\components\notifications\OrderNotification;

class Kinggems
{
    public $config;
    public function _construct($config)
    {
        $this->config = $config;
    }    

	public function createCharge($order, $user = null)
	{
        $userWalletTotal = UserWallet::find()->where([
            'user_id' => $user->id,
            'status' => UserWallet::STATUS_COMPLETED
        ])->sum('coin');
        $wallet = new UserWallet();
        $wallet->coin = (-1) * $order->total_price;
        $wallet->balance = $userWalletTotal + $wallet->coin;
        $wallet->type = UserWallet::TYPE_OUTPUT;
        $wallet->description = sprintf("Pay for order #%s", $order->id);
        $wallet->ref_name = UserWallet::REF_ORDER;
        $wallet->ref_key = $order->id;
        $wallet->user_id = $user->id;
        $wallet->status = UserWallet::STATUS_COMPLETED;
        $wallet->payment_at = date('Y-m-d H:i:s');
        $wallet->save();

        $order->status = Order::STATUS_PENDING;
        $order->payment_id = $wallet->id;
        $order->pending_at = date('Y-m-d H:i:s');

        $order->save();
        $order->log(sprintf("Verified, Status is %s", $order->status));

        // Notify to orderteam in case this is online order
        $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
        $order->pushNotification(OrderNotification::NOTIFY_ORDERTEAM_NEW_ORDER, $orderTeamIds);
        $order->pushNotification(OrderNotification::NOTIFY_CUSTOMER_PENDING_ORDER, $order->customer_id);
        return $this->getReturnUrl($order);
	}

    protected function getReturnUrl($order)
    {
        return Url::to(['cart/thankyou', 'id' => $order->id], true);
    }
}