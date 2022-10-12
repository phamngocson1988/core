<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Game;

/**
 * EditGameProfitForm is the model behind the contact form.
 */
class EditGameProfitForm extends Model
{
    public $id;
    public $expected_profit;

    protected $_game;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'expected_profit'], 'required'],
            ['id', 'validateGame'],
        ];
    }

    public function validateGame($attribute, $params = []) 
    {
        $game = $this->getGame();
        if (!$game) {
            return $this->addError($attribute, 'game_not_found');
        }
    }

    public function getGame() 
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->id);
        }
        return $this->_game;
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }
        $game = $this->getGame();
        $game->expected_profit = $this->expected_profit;
        return $game->save();
    }
}
