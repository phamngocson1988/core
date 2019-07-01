<?php
namespace frontend\components\cart;

use yii2mod\cart\models\CartItemInterface;
use yii\base\Model;
use frontend\models\Game;

class CartItem extends Game implements CartItemInterface
{
    public $quantity;
    public $username;
    public $password;
    public $character_name;
    public $recover_code;
    public $server;
    public $note;
    public $platform;
    public $login_method;

    const SCENARIO_ADD_CART = 'add_cart';
    const SCENARIO_EDIT_CART = 'edit_cart';
    const SCENARIO_INFO_CART = 'info_cart';

    public function init()
    {
        $this->quantity = ($this->quantity > 0) ? $this->quantity : 1;
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD_CART => ['id', 'quantity'],
            self::SCENARIO_EDIT_CART => ['id', 'quantity'],
            self::SCENARIO_INFO_CART => ['id', 'username', 'password', 'character_name', 'platform', 'login_method', 'server', 'recover_code', 'note'],
        ];
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['quantity'], 'required', 'on' => [self::SCENARIO_EDIT_CART, self::SCENARIO_ADD_CART]],
            ['quantity', 'number'],
            ['quantity', 'default', 'value' => 1],
            [['username', 'password', 'character_name', 'platform', 'login_method'], 'required', 'on' => self::SCENARIO_INFO_CART],
            [['server', 'recover_code', 'note'], 'trim', 'on' => self::SCENARIO_INFO_CART],
        ];
    }

    public function getTotalPrice()
    {
        return $this->getPrice() * $this->quantity;
    }

    public function getTotalUnit()
    {
        return $this->pack * $this->quantity;
    }

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        return (int)$this->price;
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