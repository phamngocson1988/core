<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Game;
use backend\models\SupplierGame;
use yii\db\Exception;

class SwitchDispatcherForm extends Model
{
    public $id;
    public $action;
    protected $_game;

    public function rules()
    {
        return [
            [['id', 'action'], 'required'],
            ['action', 'in', 'range' => ['on', 'off']],
            ['id', 'validateGame']
        ];
    }

    public function validateGame($attribute, $params)
    {
        $game = $this->getGame();
        if (!$game) {
            $this->addError($attribute, 'Game không tồn tại');
        }
    }

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->id);
        }
        return $this->_game;
    }

    public function change()
    {
        $game = $this->getGame();
        switch ($this->action) {
            case 'on':
                return $this->switchOn();
            case 'off':
                return $this->switchOff();                
            default:
                return false;
        }
    }

    public function switchOn()
    {
        $game = $this->getGame();
        $game->auto_dispatcher = Game::AUTO_DISPATCHER_ON;
        return $game->save();
    }

    public function switchOff()
    {
        $game = $this->getGame();
        $game->auto_dispatcher = Game::AUTO_DISPATCHER_OFF;
        $game->save();

        $suppliers = SupplierGame::find()->where(['game_id' => $game->id])->all();
        foreach ($suppliers as $supplier) {
            $supplier->auto_dispatcher =SupplierGame::AUTO_DISPATCHER_OFF;
            $supplier->save();
        }
        return true;
    }
}
