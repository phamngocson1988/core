<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use website\models\Paygate;
use website\models\Order;
use website\models\UserWallet;
use common\components\helpers\FormatConverter;
use website\components\payment\PaymentGatewayFactory;
use website\components\notifications\OrderNotification;
use common\models\Currency;
// Notification

class OrderPaymentForm extends Model
{
    public $cart;
    public $paygate;

    protected $_paygate;

    public function rules()
    {
        return [
            [['cart', 'paygate'], 'required'],
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

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function validatePaygate($attribute, $params = [])
    {
        $paygate = $this->getPaygate();
        if (!$paygate) {
            $this->addError($attribute, sprintf('Payment Gateway %s is not available', $this->paygate));
            return;
        }
        if ($paygate->getIdentifier() == 'kinggems') {
            $user = $this->getUser();
            $cart = $this->cart;
            $balance = $user->getWalletAmount();
            $totalPrice = $cart->getTotalPrice();
            if ($balance < $totalPrice) {
                $this->addError($attribute, 'You have not enough balance to place this order');
                return;
            }
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

    public function purchase()
    {
        $settings = Yii::$app->settings;
        $request = Yii::$app->request;
        $rate = $settings->get('ApplicationSettingForm', 'exchange_rate_vnd', 23000);
        $user = $this->getUser();
        $paygate = $this->getPaygate();

        $cart = $this->cart;
        $cartItem = $cart->getItem();
        $subTotalPrice = $cart->getSubTotalPrice();
        $fee = $paygate->getFee($subTotalPrice);
        $totalPrice = $cart->getTotalPrice() + $fee;
        $cogsPrice = $cartItem->getCogs();
        $subtotalUnit = (int)$cartItem->getSubTotalUnit();
        $totalUnit = (int)$cartItem->getTotalUnit();
        $promotion = $cartItem->getPromotion();

        $usdCurrency = Currency::findOne('USD');
        $otherCurrency = Currency::findOne($paygate->getCurrency());
        $otherCurrencyTotal = Currency::convertUSDToCurrency($totalPrice, $paygate->getCurrency());

        // Create order
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            $order = new Order();
            // paygate
            $order->payment_method = $paygate->getIdentifier();
            $order->payment_type = $paygate->getPaymentType();
            $order->payment_data = $paygate->content;
            $order->currency = $paygate->getCurrency();

            // prices
            $order->rate_usd = $rate;
            $order->price = $cartItem->getPrice();
            $order->cogs_price = $cogsPrice;
            $order->total_cogs_price = $cogsPrice * (float)$cartItem->quantity;
            $order->sub_total_price = $subTotalPrice;
            $order->total_price = $totalPrice;
            $order->total_fee = $paygate->getFee($subTotalPrice);
            $order->total_price_by_currency = $otherCurrencyTotal;

            // customer
            $order->customer_id = $user->id;
            $order->customer_name = $user->name;
            $order->customer_email = $user->email;
            $order->customer_phone = $user->phone;
            $order->user_ip = $request->userIP;

            // order information
            $order->status = Order::STATUS_VERIFYING;
            $order->payment_at = date('Y-m-d H:i:s');
            $order->generateAuthKey();

            // Item detail
            $order->game_id = $cartItem->id;
            $order->game_title = $cartItem->getLabel();
            $order->quantity = $cartItem->quantity;
            $order->unit_name = $cartItem->getUnitName();
            $order->sub_total_unit = $subtotalUnit;
            $order->promotion_unit = $totalUnit - $subtotalUnit;
            $order->total_unit = $totalUnit;
            $order->promotion_id = $promotion ? $promotion->id : null;
            $order->username = $cartItem->username;
            $order->password = $cartItem->password;
            // $order->platform = $cartItem->platform;
            $order->login_method = $cartItem->login_method;
            $order->character_name = $cartItem->character_name;
            $order->recover_code = $cartItem->recover_code;
            $order->recover_file_id = $cartItem->recover_file_id;
            $order->server = $cartItem->server;
            $order->note = $cartItem->note;
            $order->raw = $cartItem->raw;
            $order->bulk = $cartItem->bulk;

            $order->save();
            $order->log(sprintf("Created. Status %s (%s - %s)", $order->status, $paygate->getIdentifier(), $paygate->getPaymentType()));

            // Withdraw from wallet and move status to pending
            if ($paygate->getPaymentType() == 'online') {
                // $user->withdraw($totalPrice, $order->id, sprintf("Pay for order #%s", $order->id));
                $wallet = new UserWallet();
                $wallet->coin = (-1) * $totalPrice;
                $wallet->balance = $user->getWalletAmount() + $wallet->coin;
                $wallet->type = UserWallet::TYPE_OUTPUT;
                $wallet->description = sprintf("Pay for order #%s", $order->id);
                $wallet->ref_name = UserWallet::REF_ORDER;
                $wallet->ref_key = $order->id;
                $wallet->created_by = $user->id;
                $wallet->user_id = $user->id;
                $wallet->status = UserWallet::STATUS_COMPLETED;
                $wallet->payment_at = date('Y-m-d H:i:s');
                $wallet->save();

                $order->status = Order::STATUS_PENDING;
                $order->payment_id = $wallet->id;
                $order->save();
                $order->log(sprintf("Verified, Status is %s", $order->status));

                // Notify to orderteam in case this is online order
                $orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
                $order->pushNotification(OrderNotification::NOTIFY_ORDERTEAM_NEW_ORDER, $orderTeamIds);
                $order->pushNotification(OrderNotification::NOTIFY_CUSTOMER_PENDING_ORDER, $order->customer_id);
            } else {
                $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
                $order->pushNotification(OrderNotification::NOTIFY_SALER_NEW_ORDER, $salerTeamIds);
            }

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

    public function fetchPaygates()
    {
        $cart = $this->cart;
        $item = $cart->getItem();
        $paygates = Paygate::find()->where([
            'status' => Paygate::STATUS_ACTIVE,
            'currency' => $item->currency
        ])->all();
        $list = ArrayHelper::map($paygates, 'identifier', function($obj) {
            return Html::img($obj->getImageUrl(), ['class' => 'icon']);
        });

        $user = $this->getUser();
        $balance = $user->getWalletAmount();
        $totalPrice = $cart->getTotalPrice();
        if ($balance >= $totalPrice) {
            $list = array_merge([
                'kinggems' => sprintf('<div>Blance</div><div class="lead text-red font-weight-bold">%s Kcoin</div>', number_format($balance, 1))
            ], $list);
        }
        return $list;
    }
}