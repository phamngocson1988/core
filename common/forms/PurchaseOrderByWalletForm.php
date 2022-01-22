<?php
namespace common\forms;

use Yii;
use common\models\User;
use common\models\Order;
use common\models\UserWallet;

class PurchaseOrderByWalletForm extends ActionForm
{
    public $user_id; // buyer
    public $order_id; // order

    protected $_user;
    protected $_order;

    public function rules()
    {
        return [
            [['user_id', 'order_id'], 'trim'],
            [['user_id', 'order_id'], 'required'],
            ['user_id', 'validateUser'],
            ['order_id', 'validateOrder']
        ];
    }

    public function validateUser($attribute)
    {
        $user = $this->getUser();
        if (!$user) { 
            return $this->addError($attribute, 'User is not exist');
        }
    }

    public function validateOrder($attribute) 
    {
        $order = $this->getOrder();
        if (!$order) {
            return $this->addError($attribute, 'Order is not exist');
        }
        if (!$order->isVerifyingOrder()) {
            return $this->addError($attribute, 'Order is not valid');
        }
        if ($order->customer_id != $this->user_id) {
            return $this->addError($attribute, 'Order is not exist');
        }
        $user = $this->getUser();
        $balance = $user ? $user->walletBalance() : 0;
        if ($balance < $order->total_price) {
            return $this->addError($attribute, 'User has not enough wallet ballance');
        }

    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }

    public function getOrder() 
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->order_id);
        }
        return $this->_order;
    }

    public function run()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $user = $this->getUser();
        $userWalletTotal = $user->walletBalance();
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

        $paygate = new \common\components\payment\clients\Kinggems();
        $order->payment_method = $paygate->getIdentifier();
        $order->payment_type = $paygate->getPaymentType();
        $order->currency = $paygate->getCurrency();
        $order->rate_currency = $paygate->exchange_rate;
        $order->total_fee = $paygate->getFee($order->sub_total_price);
        $order->total_price = $order->sub_total_price + $order->total_fee;
        $order->total_price_by_currency = $order->total_price * $order->rate_currency;
        $order->payment_at = date('Y-m-d H:i:s');

        $order->save();
        $order->log(sprintf("Verified, Status is %s", $order->status));
        return true;
    }
}