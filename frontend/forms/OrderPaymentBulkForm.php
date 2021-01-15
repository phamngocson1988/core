<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\components\cart\CartItem;

class OrderPaymentBulkForm extends Model
{
    public $id;
    public $items = [];

    protected $_cartItem;
    protected $successList = [];
    protected $errorList = [];

    public function rules()
    {
        return [
            [['id', 'items'], 'required'],
            ['items', 'validateWallet'],
            ['items', 'validateItems']
        ];
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
        foreach ((array)$items as $index => $info) {
            $cart->clear();
            $cartItem = clone $model;
            $cartItem->quantity = ArrayHelper::getValue($info, 'quantity', 0);
            $cartItem->raw = ArrayHelper::getValue($info, 'raw', '');
            $cartItem->bulk = $bulk;
            $cart->add($cartItem);
            $checkoutForm = new \frontend\forms\OrderPaymentForm([
                'cart' => $cart, 
                'paygate' => 'kinggems'
            ]);
            if ($checkoutForm->validate() && $checkoutForm->purchase()) {
                $this->successList[] = $index;
            } else {
                $messages = $model->getErrorSummary(true);
                $message = reset($messages);
                $this->errorList[$index] = $message;
            }
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