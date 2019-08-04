<?php
namespace frontend\components\kingcoin;

use Yii;
use yii2mod\cart\models\CartItemInterface;
use yii\base\Model;
use frontend\models\Package;

class CartItem extends Package implements CartItemInterface
{
    public $quantity;

    const SCENARIO_ADD_CART = 'add_cart';
    const SCENARIO_EDIT_CART = 'edit_cart';

    public function init()
    {
        $this->quantity = ($this->quantity > 0) ? $this->quantity : 1;
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD_CART => ['quantity'],
            self::SCENARIO_EDIT_CART => ['quantity'],
        ];
    }

    public function rules()
    {
        return [
            [['quantity'], 'required'],
        ];
    }

    public function getTotalPrice()
    {
        return $this->getPrice() * $this->quantity;
    }

    public function getTotalCoin()
    {
        
        return $this->getCoin() * $this->quantity;
    }

    public function getCoin()
    {
        if (!Yii::$app->user->getIsGuest()) {
            $user = Yii::$app->user->identity;
            if ($user->isReseller()) return $this->getPrice();
        }
        return $this->num_of_coin;
    }

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        return (int)$this->amount;
    }

    public function getLabel()
    {
        return $this->title;
    }

    public function getUniqueId()
    {
        return 'item' . $this->id;
    }
}