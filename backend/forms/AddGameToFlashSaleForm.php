<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\FlashSale;
use backend\models\FlashSaleGame;
use backend\models\Game;

class AddGameToFlashSaleForm extends Model
{
    public $game_id;
    public $flashsale_id;
    public $price;
    public $limit;
    public $remain;

    protected $_flashsale;
    protected $_game;
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

    public function add()
    {
        $game = $this->getGame();
        $flashGame = new FlashSaleGame();
        $flashGame->game_id = $this->game_id;
        $flashGame->flashsale_id = $this->flashsale_id;
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
}
