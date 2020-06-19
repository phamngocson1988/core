<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Paygate;
use website\models\Order;
use common\components\helpers\FormatConverter;
// Notification

class OrderPaymentForm extends Model
{
    public $cart;
    public $paygate;

    protected $_paygate;

    public function rules()
    {
        return [
            ['cart', 'validateCart'],
            ['paygate', 'validatePaygate'],
        ];
    }

    public function getPaygate()
    {
        if (!$this->_paygate) {
            $this->_paygate = PaymentGatewayFactory::getClient($this->paygate);
        }
        return $this->_paygate;
    }

    public function validatePaygate($attribute, $params = [])
    {
        $paygate = $this->getPaygate();
        if (!$paygate) {
            $this->addError($attribute, sprintf('Payment Gateway %s is not available', $this->paygate));
        }
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

    protected isKinggems()
    {
        return $this->paygate == 'kinggems';
    }

    public function purchase()
    {
        $settings = Yii::$app->settings;
        $request = Yii::$app->request;
        $rate = $settings->get('ApplicationSettingForm', 'exchange_rate_vnd', 23000);
        $user = Yii::$app->user->getIdentity();

        $cart = $this->cart;
        $cartItem = $cart->getItem();
        $game = $cartItem->getGame();
        $subTotalPrice = $cart->getSubTotalPrice();
        $totalPrice = $cart->getTotalPrice();
        $fee = $this->isKinggems() ? '0' : $this->getPaygate()->getFee($subTotalPrice);
        $cogsPrice = $game->getCogs();
        $totalUnit = $cartItem->getTotalUnit();

        // Create order
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            // Order detail
            $order = new Order();
            $order->payment_method = $this->paygate;
            $order->payment_type = $this->isKinggems() ? 'online' : 'offline';
            $order->rate_usd = $rate;
            $order->price = $cartItem->getPrice();
            $order->cogs_price = $cogsPrice;
            $order->sub_total_price = $subTotalPrice;
            $order->total_price = $totalPrice;
            $order->total_fee = $fee;
            $order->total_price_by_currency = FormatConverter::convertCurrencyToCny($totalPrice);
            $order->currency = 'USD';
            $order->total_cogs_price = $cogsPrice * (float)$cartItem->quantity;
            $order->customer_id = $user->id;
            $order->customer_name = $user->name;
            $order->customer_email = $user->email;
            $order->customer_phone = $user->phone;
            $order->user_ip = $request->userIP;
            $order->status = Order::STATUS_VERIFYING;
            $order->payment_at = date('Y-m-d H:i:s');
            $order->generateAuthKey();

            // Item detail
            $order->game_id = $game->id;
            $order->game_title = $game->title;
            $order->quantity = $this->quantity;
            $order->unit_name = $game->unit_name;
            $order->sub_total_unit = $totalUnit;
            $order->promotion_unit = 0;
            $order->total_unit = $totalUnit;
            $order->promotion_id = $cart->hasPromotion() ? $cart->getPromotionItem()->id : null;
            $order->username = $cartItem->username;
            $order->password = $cartItem->password;
            $order->platform = $cartItem->platform;
            $order->login_method = $cartItem->login_method;
            $order->character_name = $cartItem->character_name;
            $order->recover_code = $cartItem->recover_code;
            $order->server = $cartItem->server;
            $order->note = $cartItem->note;

            $order->save();
            $order->log(sprintf("Created. Status %s (%s - %s)", $order->status, $identifier, $gateway->type));

            // Withdraw from wallet and move status to pending
            if ($this->isKinggems()) {
                $user->withdraw($totalPrice, $order->id, sprintf("Pay for order #%s", $order->id));
                $order->status = Order::STATUS_PENDING;
                $order->payment_at = date('Y-m-d H:i:s');
                $order->payment_id = $gateway->getPaymentId();
                $order->save();
                $order->log(sprintf("Verified, Status is %s", $order->status));

                // Notify to orderteam in case this is online order
                $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
                $order->pushNotification(OrderNotification::NOTIFY_ORDERTEAM_NEW_ORDER, $orderTeamIds);
                $order->pushNotification(OrderNotification::NOTIFY_CUSTOMER_PENDING_ORDER, $order->customer_id);

                $order->save();
            } else {
                $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
                $order->pushNotification(OrderNotification::NOTIFY_SALER_NEW_ORDER, $salerTeamIds);
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