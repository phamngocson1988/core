<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Game;

class AddCartForm extends Model
{
    public $game_id;
    public $quantity = 1;
    public $voucher;
    protected $_game;

    public function rules()
    {
        return [
            [['game_id', 'quantity'], 'required'],
            ['game_id', 'validateGame'],
            ['quantity', 'number'],
            ['voucher', 'trim']
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

    public function add()
    {
        $cart = Yii::$app->cart;
        $cart->clear();
        $item = new \website\components\cart\CartItem([
            'game' => $this->getGame(),
            'quantity' => $this->quantity
        ]);
        $cart->add($item);
    }

    public function calculate()
    {
        $game = $this->getGame();
        $quantity = $this->quantity;
        return $game->getPrice() * $quantity;
    }
}

