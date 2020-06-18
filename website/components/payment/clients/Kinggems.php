<?php
namespace website\components\payment\clients;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\base\Exception;
use website\components\payment\PaymentGateway;
use yii\helpers\Url;
use website\models\UserWallet;
use website\models\Order;

class Kinggems extends PaymentGateway
{
    public $identifier = 'kinggems';
    public $type = 'online';
	public $currency = 'KINGGEMS';

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
                'auth_key' => $refKey,
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
                return $this->redirect($this->getConfirmUrl(['paymentId' => $wallet->id]));
            }
        } catch (\Exception $e) {
            return $this->redirect($this->getErrorUrl());
        }
    }
    
    protected function verify($response)
    {
        extract($response); // now can use $paymentId, $PayerID, $token
        if (!$paymentId) return false;
        $this->setPaymentId($paymentId);
        return true;
    }

    public function cancelPayment()
    {
        return true;
    }

    public function doSuccess()
    {
        $refId = $this->getReferenceId();
        return $this->redirect($this->getSuccessUrl(['ref' => $refId]));
    }

    public function doError()
    {
        return $this->redirect($this->getErrorUrl());
    }

    public function getFee($total)
    {
        return 0;
    }

}