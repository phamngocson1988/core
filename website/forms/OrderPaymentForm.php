<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use website\models\Paygate;
use website\models\Order;
use website\models\User;
use website\models\UserWallet;
use website\models\PromotionApply;
use common\components\helpers\FormatConverter;
use website\components\payment\PaymentGatewayFactory;
use website\components\notifications\OrderNotification;
// use common\models\Currency;
use common\components\helpers\StringHelper;
use common\models\PaymentCommitment;
use common\models\CurrencySetting;

// Notification

class OrderPaymentForm extends Model
{
    public $cart;
    public $paygate;
    public $isBulk = false;

    protected $_paygate;

    public function rules()
    {
        return [
            [['cart', 'paygate'], 'required'],
            ['cart', 'validateCart'],
            ['paygate', 'validatePaygate'],
        ];
    }

    public function getPaygateConfig()
    {
        if (!$this->_paygate) {
            $this->_paygate = PaymentGatewayFactory::getConfig($this->paygate);
        }
        return $this->_paygate;
    }

    public function getPaygate()
    {
        $config = $this->getPaygateConfig();
        return PaymentGatewayFactory::getPaygate($config);
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    public function validatePaygate($attribute, $params = [])
    {
        $paygate = $this->getPaygateConfig();
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

        if ($cart->getTotalPrice() <=0) {
            return $this->addError($attribute, 'Total price is not valid');
        }
        
        // Check price
        if ($this->paygate === 'coinspaid') {
            $paygate = $this->getPaygateConfig();
            $subTotalPrice = $cart->getSubTotalPrice();
            $fee = $paygate->getFee($subTotalPrice);
            $totalPrice = $cart->getTotalPrice() + $fee;
            if ($totalPrice < 15) {
                $this->addError($attribute, 'The payment must be greater than or equal to 15 USD');
            }
        }
    }

    public function calculate()
    {
        $cart = $this->cart;
        $paygate = $this->getPaygateConfig();
        $subTotalPrice = $cart->getSubTotalPrice();
        $totalPrice = $cart->getTotalPrice();
        $promotionDiscount = $cart->getPromotionDiscount();
        $fee = $paygate->getFee($subTotalPrice);
        $totalPrice += $fee;
        return [
            'subTotalPayment' => $subTotalPrice,
            'promotionDiscount' => $promotionDiscount,
            'transferFee' => $fee,
            'totalPayment' => $totalPrice,
        ];
    }

    public function purchase()
    {
        $settings = Yii::$app->settings;
        $request = Yii::$app->request;
        $user = $this->getUser();
        $paygate = $this->getPaygateConfig();

        $cart = $this->cart;
        $cartItem = $cart->getItem();
        $subTotalPrice = $cart->getSubTotalPrice();
        $discount = $cart->getPromotionDiscount();
        $fee = $paygate->getFee($subTotalPrice);
        $totalPrice = $cart->getTotalPrice() + $fee;
        $cogsPrice = $cartItem->getCogs();
        $subtotalUnit = (int)$cartItem->getSubTotalUnit();
        $totalUnit = (int)$cartItem->getTotalUnit();
        $promotion = $cartItem->getPromotion();

        // $usdCurrency = Currency::findOne('USD');
        // $targetCurrency = Currency::findOne($paygate->getCurrency());

        $usdCurrency = CurrencySetting::findOne(['code' => 'USD']);
        $targetCurrency = CurrencySetting::findOne(['code' => $paygate->getCurrency()]);
        $vndCurrency = CurrencySetting::findOne(['code' => 'VND']);
        $targetCurrencyTotal = $usdCurrency->exchangeTo($totalPrice, $targetCurrency);
        $rate = $settings->get('ApplicationSettingForm', 'exchange_rate_vnd', 23000);
        if ($vndCurrency) {
            $rate = $vndCurrency->exchange_rate;
        }
        // Create order
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $order = new Order();
            // paygate
            $order->payment_method = $paygate->getIdentifier();
            $order->payment_type = $paygate->getPaymentType();
            $order->payment_content = $paygate->content;
            $order->currency = $paygate->getCurrency();
            $order->rate_currency = $settings->get('ApplicationSettingForm', sprintf('exchange_rate_%s', strtolower($order->currency)), 1);

            // prices
            $order->rate_usd = $rate;
            $order->price = $cartItem->getPrice();
            $order->flash_sale = !!$cartItem->getFlashSalePrice();
            $order->cogs_price = $cogsPrice;
            $order->total_cogs_price = $cogsPrice * (float)$cartItem->quantity;
            $order->sub_total_price = $subTotalPrice;
            $order->total_price = $totalPrice;
            $order->total_discount = $discount;
            $order->total_fee = $paygate->getFee($subTotalPrice);
            $order->total_price_by_currency = $targetCurrencyTotal;

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
            $order->original_quantity = $cartItem->quantity;
            $order->quantity = $cartItem->quantity;
            $order->unit_name = $cartItem->getUnitName();
            $order->sub_total_unit = $subtotalUnit;
            $order->promotion_unit = $totalUnit - $subtotalUnit;
            $order->total_unit = $totalUnit;
            $order->promotion_id = $promotion ? $promotion->id : null;
            $order->promotion_code = $promotion ? $promotion->code : null;
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

            // Check saler
            $reseller = $user->reseller; 
            if ($reseller) {
                $order->saler_id = $reseller->manager_id;
            } elseif ($cartItem->saler_code && !$order->saler_id) {
                $invitor = User::findOne(['saler_code' => trim($cartItem->saler_code)]);
                $order->saler_id = ($invitor) ? $invitor->id : null;
            }

            $order->save();
            $order->log(sprintf("Created. Status %s (%s - %s)", $order->status, $paygate->getIdentifier(), $paygate->getPaymentType()));

            // Withdraw from wallet and move status to pending
            if (!$paygate->isOnline()) {
                $salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
                $order->pushNotification(OrderNotification::NOTIFY_SALER_NEW_ORDER, $salerTeamIds);
            }

            if ($paygate->getIdentifier() != 'kinggems' && !$this->isBulk) {
                $commitment = new PaymentCommitment();
                $commitment->object_name = PaymentCommitment::OBJECT_NAME_ORDER;
                $commitment->object_key = $order->id;
                $commitment->paygate = $paygate->getIdentifier();
                $commitment->payment_type = $paygate->getPaymentType();
                $commitment->amount = $usdCurrency->exchangeTo($order->sub_total_price, $targetCurrency); //Currency::convertUSDToCurrency($order->sub_total_price, $order->currency);
                $commitment->fee = $usdCurrency->exchangeTo($order->total_fee, $targetCurrency); //Currency::convertUSDToCurrency($order->sub_total_price, $order->total_fee);
                $commitment->total_amount = $usdCurrency->exchangeTo($order->total_price, $targetCurrency);
                $commitment->currency = $order->currency;
                $commitment->kingcoin = $usdCurrency->getKcoin($order->total_price);
                $commitment->exchange_rate = $targetCurrency->exchange_rate;
                $commitment->user_id = $order->customer_id;
                $commitment->status = PaymentCommitment::STATUS_PENDING;
                $commitment->save();
            }

            // Check flashsale
            $flashsale = $cartItem->getFlashSalePrice();
            if ($flashsale && $flashsale->limit) {
                $flashsale->remain = min($flashsale->limit, $flashsale->remain) - 1;
                $flashsale->save();
            }

            // Mark promotion is used by user
            if ($promotion) {
                $promotionApply = new PromotionApply();
                $promotionApply->promotion_id = $promotion->id;
                $promotionApply->user_id = Yii::$app->user->id;
                $promotionApply->save();
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
        // if (!in_array(Yii::$app->user->id, [213, 910])) {
        //     unset($list['webmoney']);
        //     unset($list['binance']);
        // }
        $user = $this->getUser();
        $balance = $user->getWalletAmount();
        $totalPrice = $cart->getTotalPrice();
        if ($balance >= $totalPrice) {
            $list = array_merge([
                'kinggems' => sprintf('<div>Blance</div><div class="lead text-red font-weight-bold">%s Kcoin</div>', StringHelper::numberFormat($balance, 2))
            ], $list);
        }
        return $list;
    }
}