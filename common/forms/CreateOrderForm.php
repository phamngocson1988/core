<?php
namespace common\forms;

use Yii;
use yii\base\Model;
use common\models\Order;
use common\models\User;
use common\models\CurrencySetting;

class CreateOrderForm extends ActionForm
{
    public $cart;
    public $user_ip;


    public function rules()
    {
        return [
            [['cart'], 'required'],
            ['cart', 'validateCart'],
            ['user_ip', 'safe']
        ];
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function validateCart($attribute, $params = [])
    {
        $cart = $this->cart;
        if (!$cart) {
            $this->addError($attribute, 'Cart is empty');
            return;
        }
        if (!$cart->getCount()) {
            $this->addError($attribute, 'Cart is empty');
            return;
        }
    }

    public function purchase()
    {
        if (!$this->validate()) return false;
        $user = $this->getUser();
        $cart = $this->cart;
        $cartItem = $cart->getItem();
        $subTotalPrice = $cart->getSubTotalPrice();
        $totalPrice = $cart->getTotalPrice();
        $cogsPrice = $cartItem->getCogs();
        $subtotalUnit = (int)$cartItem->getSubTotalUnit();
        $totalUnit = (int)$cartItem->getTotalUnit();

        $usdCurrency = CurrencySetting::findOne(['code' => 'USD']);
        $vndCurrency = CurrencySetting::findOne(['code' => 'VND']);
        $rate = $vndCurrency->exchange_rate;
        // Create order
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            $order = new Order();
            // prices
            $order->rate_usd = $rate;
            $order->price = $cartItem->getPrice();
            $order->flash_sale = !!$cartItem->getFlashSalePrice();
            $order->cogs_price = $cogsPrice;
            $order->total_cogs_price = $cogsPrice * (float)$cartItem->quantity;
            $order->sub_total_price = $subTotalPrice;
            $order->total_price = $totalPrice;

            // customer
            $order->customer_id = $user->id;
            $order->customer_name = $user->name;
            $order->customer_email = $user->email;
            $order->customer_phone = $user->phone;
            $order->user_ip = $this->user_ip;

            // order information
            $order->status = Order::STATUS_VERIFYING;
            $order->generateAuthKey();

            // Item detail
            $order->game_id = $cartItem->id;
            $order->game_title = $cartItem->getLabel();
            $order->original_quantity = $cartItem->quantity;
            $order->quantity = $cartItem->quantity;
            $order->unit_name = $cartItem->getUnitName();
            $order->sub_total_unit = $subtotalUnit;
            $order->total_unit = $totalUnit;
            $order->username = $cartItem->username;
            $order->password = $cartItem->password;
            $order->login_method = $cartItem->login_method;
            $order->character_name = $cartItem->character_name;
            $order->recover_code = $cartItem->recover_code;
            $order->recover_file_id = $cartItem->recover_file_id;
            $order->server = $cartItem->server;
            $order->note = $cartItem->note;
            $order->raw = $cartItem->raw;
            $order->bulk = $cartItem->bulk;

            // Check saler
            $reseller = $user->reseller; 
            if ($reseller) {
                $order->saler_id = $reseller->manager_id;
            } elseif ($cartItem->saler_code && !$order->saler_id) {
                $invitor = User::findOne(['saler_code' => trim($cartItem->saler_code)]);
                $order->saler_id = ($invitor) ? $invitor->id : null;
            }

            $order->save();
            $order->log(sprintf("[CreateOrderForm] API Created. Status %s", $order->status));


            // Check flashsale
            $flashsale = $cartItem->getFlashSalePrice();
            if ($flashsale && $flashsale->limit) {
                $flashsale->remain = min($flashsale->limit, $flashsale->remain) - 1;
                $flashsale->save();
            }

            $transaction->commit();
            return $order->id;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('cart', $e->getMessage());
            return false;
        }
    }
}