<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\FlashSale;
use backend\models\FlashSaleGame;
use backend\models\Game;

class EditGameToFlashSaleForm extends Model
{
    public $id;
    public $game_id;
    public $flashsale_id;
    public $price;
    public $limit;
    public $remain;

    protected $_flashsale;
    protected $_game;
    protected $_flashsalegame;
    private $_command;

    public function rules()
    {
        return [
            [['flashsale_id', 'game_id'], 'required'],
            [['price', 'limit', 'remain'], 'safe']
    
        ];
    }

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->game_id);
        }
        return $this->_game;
    }

    public function getFlashSaleGame()
    {
        if (!$this->_flashsalegame) {
            $this->_flashsalegame = FlashSaleGame::findOne($this->id);
        }
        return $this->_flashsalegame;
    }

    public function edit()
    {
        $game = $this->getGame();
        $flashGame = $this->getFlashSaleGame();
        $flashGame->price = $this->price ? $this->price : $game->price;
        $flashGame->limit = $this->limit;
        $flashGame->remain = $this->remain;
        return $flashGame->save();
    }

    public function fetchGame()
    {
        $models = Game::find()->all();
        return ArrayHelper::map($models, 'id', 'title');
    }

    public function loadData()
    {
        $flashGame = $this->getFlashSaleGame();
        $this->game_id = $flashGame->game_id;
        $this->flashsale_id = $flashGame->flashsale_id;
        $this->price = $flashGame->price;
        $this->limit = $flashGame->limit;
        $this->remain = $flashGame->remain;
    }
}
