<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use website\components\cart\CartItem;
use website\models\Order;
use website\components\payment\PaymentGatewayFactory;
use common\models\PaymentCommitment;
use common\models\CurrencySetting;


class OrderPaymentBulkForm extends Model
{
    public $id;
    public $items = [];
    public $paygate = 'kinggems';

    protected $_cartItem;
    protected $successList = [];
    protected $errorList = [];
    protected $_paygate;

    public function rules()
    {
        return [
            [['id', 'items'], 'required'],
            ['items', 'validateItems'],
            ['paygate', 'validatePaygate'],
        ];
    }

    public function validatePaygate($attribute, $params = [])
    {
        $paygate = $this->getPaygateConfig();
        if (!$paygate) {
            $this->addError($attribute, sprintf('Payment Gateway %s is not available', $this->paygate));
            return;
        }
        if ($paygate->getIdentifier() == 'kinggems') {
            return $this->validateWallet($attribute, $params);
        }
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

    public function validateWallet($attribute, $params = [])
    {
        $user = Yii::$app->user->getIdentity();
        $balance = $user->getWalletAmount();
        $model = CartItem::findOne($this->id);
        $quantities = array_column($this->items, 'quantity');
        $quantity = array_sum($quantities);
        $model->quantity = $quantity;
        $total = $model->getTotalPrice();
        if ($balance < $total) {
            $this->addError($attribute, 'You have not enough balance in wallet to process these orders');
        }
    }

    public function validateItems($attribute, $params = [])
    {
        $model = $this->getCartItem();
        $cart = Yii::$app->cart;
        $items = $this->getItems();
        foreach ((array)$items as $info) {
            $cart->clear();
            $cartItem = clone $model;
            $cartItem->quantity = ArrayHelper::getValue($info, 'quantity', 0);
            $cartItem->raw = ArrayHelper::getValue($info, 'raw', '');
            if (!$cartItem->validate()) {
                $messages = $cartItem->getErrorSummary(true);
                $message = reset($messages);
                $this->addError($attribute, $message);
                return;
            }
        }
    }

    public function purchase()
    {
        $items = $this->getItems();
        $model = $this->getCartItem();
        $cart = Yii::$app->cart;
        $bulk = strtotime('now');
        $user = Yii::$app->user->getIdentity();
        $paygate = $this->getPaygateConfig();
        foreach ((array)$items as $index => $info) {
            $cart->clear();
            $cartItem = clone $model;
            $cartItem->quantity = ArrayHelper::getValue($info, 'quantity', 0);
            $cartItem->raw = ArrayHelper::getValue($info, 'raw', '');
            $cartItem->bulk = $bulk;
            $cart->add($cartItem);
            $checkoutForm = new \website\forms\OrderPaymentForm([
                'cart' => $cart, 
                'paygate' => $this->paygate,
                'isBulk' => true
            ]);
            if ($checkoutForm->validate() && $id = $checkoutForm->purchase()) {
                $this->successList[] = $index;
                $paygate = $checkoutForm->getPaygate();
                $order = Order::findOne($id);
                $paygate->createCharge($order, $user);
            } else {
                $messages = $model->getErrorSummary(true);
                $message = reset($messages);
                $this->errorList[$index] = $message;
            }
        }
        $usdCurrency = CurrencySetting::findOne(['code' => 'USD']);
        $targetCurrency = CurrencySetting::findOne(['code' => $paygate->getCurrency()]);
        $vndCurrency = CurrencySetting::findOne(['code' => 'VND']);
        $sub_total_price = 0; // total of sub price
        $total_fee = 0; // total of fee
        $total_price = 0; // total of price
        if ($paygate->getIdentifier() != 'kinggems') {
            $commitment = new PaymentCommitment();
            $commitment->object_name = PaymentCommitment::OBJECT_NAME_ORDER;
            $commitment->object_key = ''; // list of $order->id;
            $commitment->paygate = $paygate->getIdentifier();
            $commitment->payment_type = $paygate->getPaymentType();
            $commitment->amount = $usdCurrency->exchangeTo($sub_total_price, $targetCurrency);
            $commitment->fee = $usdCurrency->exchangeTo($total_fee, $targetCurrency);
            $commitment->total_amount = $usdCurrency->exchangeTo($total_price, $targetCurrency);
            $commitment->currency = $paygate->getCurrency();
            $commitment->kingcoin = $usdCurrency->getKcoin($total_price);
            $commitment->exchange_rate = $targetCurrency->exchange_rate;
            $commitment->user_id = $user->id;
            $commitment->status = PaymentCommitment::STATUS_PENDING;
            $commitment->save();
        }
        return true;
    }

    public function getItems()
    {
        return (array)$this->items;
    }

    public function getCartItem()
    {
        if (!$this->_cartItem) {
            $this->_cartItem = CartItem::findOne($this->id);
            $this->_cartItem->setScenario(CartItem::SCENARIO_BULK_CART);
        }
        return $this->_cartItem;
    }

    public function getSuccessList()
    {
        return (array)$this->successList;
    }

    public function getErrorList()
    {
        return (array)$this->errorList;
    }
}