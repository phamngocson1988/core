<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Order;
use common\models\User;
use frontend\models\UserWallet;
use frontend\components\cart\Cart;

class PurchaseGameForm extends Model
{
    const EVENT_AFTER_PURCHASE = 'EVENT_AFTER_PURCHASE';

    public $cart;
    public $user;
    protected $_order;
    protected $_wallet;

    public function rules()
    {
        return [
            ['user', 'validateUser'],
            ['cart', 'validateCart'],
        ];
    }

    public function validateCart($attribute, $params = null) 
    {
        if ($this->hasErrors()) return false;
        $cart = $this->cart;
        $user = $this->user;
        if (!($cart instanceof Cart)) $this->addError($attribute, 'There is something wrong with cart');
        if (!$cart->getItem()) $this->addError($attribute, 'Cart is empty');
        if ($cart->hasPromotion()) $cart->applyPromotion();
        if ($user->getWalletAmount() < $cart->getTotalPrice()) $this->addError($attribute, 'Not enough money in your wallet');
    }

    public function validateUser($attribute, $params = null) 
    {
        $user = $this->user;
        if (!($user instanceof User)) $this->addError($attribute, 'There is something wrong with user');
    }

    public function getOrder()
    {
        return $this->_order;
    }

    public function setOrder($order)
    {
        $this->_order = $order;
    }

    public function getWallet()
    {
        return $this->_wallet;
    }

    public function setWallet($wallet)
    {
        $this->_wallet = $wallet;
    }

    public function purchase()
    {
        if (!$this->validate()) return false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Create order
            $user = Yii::$app->user->getIdentity();
            $cart = Yii::$app->cart;
            $cartItem = $cart->getItem();
            if ($cart->hasPromotion()) $cart->applyPromotion();
            $totalPrice = $cart->getTotalPrice();
            $subTotalPrice = $cart->getSubTotalPrice();
            $promotionCoin = $cart->getPromotionCoin();
            $promotionUnit = $cart->getPromotionUnit();

            // Order detail
            $order = new Order();
            $order->sub_total_price = $subTotalPrice;
            $order->total_discount = $promotionCoin;
            $order->total_price = $totalPrice;
            $order->customer_id = $user->id;
            $order->customer_name = $user->name;
            $order->customer_email = $cartItem->reception_email;
            $order->customer_phone = $user->phone;
            $order->status = Order::STATUS_PENDING;
            $order->payment_at = date('Y-m-d H:i:s');
            $order->generateAuthKey();

            // Item detail
            $order->game_id = $cartItem->id;
            $order->game_title = $cartItem->getLabel();
            $order->quantity = $cartItem->quantity;
            $order->unit_name = $cartItem->unit_name;
            $order->sub_total_unit = $cartItem->getTotalUnit();
            $order->promotion_unit = $promotionUnit;
            $order->total_unit = $cartItem->getTotalUnit() + $promotionUnit;
            $order->username = $cartItem->username;
            $order->password = $cartItem->password;
            $order->platform = $cartItem->platform;
            $order->login_method = $cartItem->login_method;
            $order->character_name = $cartItem->character_name;
            $order->recover_code = $cartItem->recover_code;
            $order->server = $cartItem->server;
            $order->note = $cartItem->note;

            if (!$order->save()) throw new BadRequestHttpException("Error Processing Request", 1);

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
            $wallet->save();
            $transaction->commit();
            
            $this->setOrder($order);
            $this->setWallet($wallet);
            $this->trigger(self::EVENT_AFTER_PURCHASE);
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}