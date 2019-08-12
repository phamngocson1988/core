<?php
namespace frontend\components\payment\clients;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\base\Exception;
use frontend\components\payment\PaymentGateway;
use yii\helpers\Url;
use frontend\models\UserWallet;
use frontend\models\Order;

class Kinggems extends PaymentGateway
{
    public $identifier = 'kinggems';

    public function validatePayment()
    {
        $user = Yii::$app->user->getIdentity();
        $cart = $this->cart;
        $totalPrice = $cart->getTotalPrice();
        $wallet = $user->getWalletAmount();
        if ($totalPrice > $wallet) {
            $this->addError('*', 'Not enough coins to pay this order');
            return false;
        }
        return true;
    }

    protected function sendRequest()
    {
        try {
            $cart = $this->cart;
            $totalPrice = $cart->getTotalPrice();
            $user = Yii::$app->user->getIdentity();
            $refKey = $this->getReferenceId();
            $order = Order::findOne([
                'payment_method' => $this->identifier,
                'payment_id' => $refKey,
                'status' => Order::STATUS_VERIFYING
            ]);
            if (!$order) throw new Exception("Order is not exist", 1);
            
            $wallet = new UserWallet();
            $wallet->coin = (-1) * $totalPrice;
            $wallet->balance = $user->getWalletAmount() + $wallet->coin;
            $wallet->type = UserWallet::TYPE_OUTPUT;
            $wallet->description = "Pay for order #$order->id";
            $wallet->ref_name = Order::classname();
            $wallet->ref_key = $order->id;
            $wallet->created_by = $user->id;
            $wallet->user_id = $user->id;
            $wallet->status = UserWallet::STATUS_COMPLETED;
            $wallet->payment_at = date('Y-m-d H:i:s');
            if ($wallet->save()) {
                return $this->redirect($this->getConfirmUrl());
            }
        } catch (\Exception $e) {
            return $this->redirect($this->getErrorUrl());
        }
    }
    
    protected function verify($response)
    {
        return true;
    }

    public function cancelPayment()
    {
        return true;
    }

    public function doSuccess()
    {
        return $this->redirect($this->getSuccessUrl());
    }

    public function doError()
    {
        return $this->redirect($this->getErrorUrl());
    }

}