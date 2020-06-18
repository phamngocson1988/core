<?php
namespace website\components\cart;

use Yii;
use yii2mod\cart\models\CartItemInterface;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use website\models\Game;

class CartItem extends Model implements CartItemInterface
{
    const SCENARIO_CALCULATE_CART = 'SCENARIO_CALCULATE_CART';
    const SCENARIO_ADD_CART = 'SCENARIO_ADD_CART';
    const SCENARIO_UPDATE_CART = 'SCENARIO_UPDATE_CART';

    public $game_id;
    public $quantity = 1;
    public $username;
    public $password;
    public $character_name;
    public $recover_code;
    public $server;
    public $note;
    public $login_method;
    public $voucher;
    protected $_game;

    public function scenarios()
    {
        return [
            self::SCENARIO_CALCULATE_CART => ['game_id', 'quantity', 'voucher'],
            self::SCENARIO_ADD_CART => ['game_id', 'quantity'],
            self::SCENARIO_UPDATE_CART => ['game_id', 'quantity', 'username', 'password', 'character_name', 'login_method', 'server', 'recover_code', 'note', 'voucher'],
        ];
    }

    public function rules()
    {
        return [
            [['game_id', 'quantity'], 'required'],
            ['game_id', 'validateGame'],
            ['quantity', 'number'],
            [['server', 'note', 'login_method', 'recover_code', 'voucher'], 'trim'],
            [['username', 'password', 'character_name'], 'required', 'on' => self::SCENARIO_UPDATE_CART],

        ];
    }

    public function validateGame($attribute, $params = [])
    {
        $game = $this->getGame();
        if (!$game) {
            $this->addError($attribute, 'This game is not found.');
            return;
        }
    }

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->game_id);
        }
        return $this->_game;
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

    public function getUnit() 
    {
        $game = $this->getGame();
        return $game->pack;
    }

    public function getTotalUnit()
    {
        $unit = $this->getUnit();
        $quantity = $this->quantity;
        return $unit * $quantity;
    }

    public function getUnitName()
    {
        $game = $this->getGame();
        return $game->unit_name;
    }

    public function getTotalPrice()
    {
        $game = $this->getGame();
        $quantity = $this->quantity;
        return $game->getPrice() * $quantity;
    }

    /**
     * Returns the price for the cart item
     */
    public function getPrice() 
    {
        $game = $this->getGame();
        return $game->getPrice();
    }

    /**
     * Returns the label for the cart item (displayed in cart etc)
     *
     * @return int|string
     */
    public function getLabel() 
    {
        $game = $this->getGame();
        return $game->title;
    }

    /**
     * Returns unique id to associate cart item with product
     *
     * @return int|string
     */
    public function getUniqueId() 
    {
        $game = $this->getGame();
        return $game->id;
    }

}