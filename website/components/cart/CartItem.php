<?php
namespace website\components\cart;

use Yii;
use yii2mod\cart\models\CartItemInterface;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use website\models\Game;

class CartItem extends Game implements CartItemInterface
{
    const SCENARIO_CALCULATE_CART = 'SCENARIO_CALCULATE_CART';
    const SCENARIO_ADD_CART = 'SCENARIO_ADD_CART';
    const SCENARIO_UPDATE_CART = 'SCENARIO_UPDATE_CART';
    const SCENARIO_BULK_CART = 'SCENARIO_BULK_CART';

    public $quantity = 1;
    public $username;
    public $password;
    public $character_name;
    public $recover_code;
    public $recover_file_id;
    public $server;
    public $note;
    public $login_method;
    public $voucher;
    public $currency;
    public $raw;
    public $bulk;
    protected $_game;

    public function scenarios()
    {
        return [
            self::SCENARIO_CALCULATE_CART => ['id', 'quantity', 'voucher', 'currency'],
            self::SCENARIO_ADD_CART => ['id', 'quantity', 'currency'],
            self::SCENARIO_UPDATE_CART => ['id', 'quantity', 'username', 'password', 'character_name', 'login_method', 'server', 'recover_code', 'recover_file_id', 'note', 'voucher'],
            self::SCENARIO_BULK_CART => ['id', 'quantity', 'raw', 'bulk'],
        ];
    }

    public function rules()
    {
        return [
            ['quantity', 'number'],
            [['server', 'note', 'recover_code', 'recover_file_id', 'voucher', 'currency', 'bulk'], 'trim'],
            [['username', 'password', 'character_name', 'login_method'], 'required', 'on' => self::SCENARIO_UPDATE_CART],
            [['quantity', 'raw'], 'required', 'on' => self::SCENARIO_BULK_CART],

        ];
    }

    public function fetchLoginMethod()
    {
        return [
            'facebook' => 'Facebook',
            'google' => 'Google',
            'other' => 'Other methods',
        ];
    }

    public function fetchPlatform()
    {
        return [
            'android' => 'Android',
            'ios' => 'IOS'
        ];
    }

    public function fetchCurrency()
    {
        return [
            'USD' => 'USD',
            'CNY' => 'CNY',
        ];
    }

    public function getUnit() 
    {
        return $this->pack;
    }

    public function getTotalUnit()
    {
        $unit = $this->getUnit();
        $quantity = $this->quantity;
        return $unit * $quantity;
    }

    public function getUnitName()
    {
        return $this->unit_name;
    }

    public function getTotalOriginalPrice()
    {
        return $this->getOriginalPrice() * $this->quantity;
    }

    public function getTotalPrice()
    {
        $quantity = $this->quantity;
        return $this->getPrice() * $quantity;
    }

    /**
     * Returns the price for the cart item
     */
    public function getPrice() 
    {
        return parent::getPrice();
    }

    /**
     * Returns the label for the cart item (displayed in cart etc)
     *
     * @return int|string
     */
    public function getLabel() 
    {
        return $this->title;
    }

    /**
     * Returns unique id to associate cart item with product
     *
     * @return int|string
     */
    public function getUniqueId() 
    {
        return $this->id;
    }

}