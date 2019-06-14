<?php
namespace frontend\components\cart;

use yii2mod\cart\models\CartItemInterface;
use yii\base\Model;
use common\models\Game;
use common\models\Product;

class CartItem extends Model implements CartItemInterface
{
    public $game_id;
    public $quantity;
    public $username;
    public $password;
    public $character_name;
    public $recover_code;
    public $server;
    public $note;
    public $platform;
    public $login_method;

    const SCENARIO_ADD = 'add';
    const SCENARIO_EDIT = 'edit';
    const SCENARIO_INFO = 'info';

    /** @var Game **/
    protected $_game;

    public function init()
    {
        $this->quantity = ($this->quantity > 0) ? $this->quantity : 1;
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => ['game_id', 'quantity'],
            self::SCENARIO_EDIT => ['game_id', 'quantity'],
            self::SCENARIO_INFO => ['game_id', 'username', 'password', 'character_name', 'platform', 'login_method', 'server', 'recover_code', 'note'],
        ];
    }

    public function rules()
    {
        return [
            [['game_id'], 'required'],
            [['quantity'], 'required', 'on' => [self::SCENARIO_EDIT, self::SCENARIO_ADD]],
            ['quantity', 'number'],
            ['quantity', 'default', 'value' => 1],
            [['username', 'password', 'character_name', 'platform', 'login_method'], 'required', 'on' => self::SCENARIO_INFO],
            [['server', 'recover_code', 'note'], 'trim', 'on' => self::SCENARIO_INFO],
            ['game_id', 'validateGame'],
        ];
    }

    public function validateGame($attribute, $params)
    {
        $game = $this->getGame();
        if (!$game) {
            $this->addError($attribute, 'Not found');
        }
    }

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->game_id);
        }
        return $this->_game;
    }

    public function setQuantity($num)
    {
        $this->quantity = max(1, (int)$num);
    }

    public function increase()
    {
        $this->setQuantity($this->quantity++);
    }

    public function descrease()
    {
        $this->setQuantity($this->quantity--);
    }

    public function getTotalPrice()
    {
        return $this->getPrice() * $this->quantity;
    }

    public function getUnitName()
    {
        return $this->getGame()->unit_name;
    }

    public function getTotalPack()
    {
        return $this->getGame()->pack * $this->quantity;
    }

    public function getGameId()
    {
        return $this->game_id;
    }

    // ============== implement interface ===========//
    public function getPrice() : int
    {
        return (int)$this->getGame()->price;
    }

    public function getLabel()
    {
        return $this->getGame()->title;
    }

    public function getUniqueId()
    {
        return 'item' . $this->game_id;
    }
}