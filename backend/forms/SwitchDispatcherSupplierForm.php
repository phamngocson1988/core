<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Game;
use backend\models\SupplierGame;
use yii\db\Exception;

class SwitchDispatcherSupplierForm extends Model
{
    public $game_id;
    public $supplier_id;
    public $action;
    protected $_game;
    protected $_supplierGame;

    public function rules()
    {
        return [
            [['game_id', 'action', 'supplier_id'], 'required'],
            ['action', 'in', 'range' => ['on', 'off']],
            ['supplier_id', 'validateSupplierGame'],
        ];
    }

    public function validateSupplierGame($attribute, $params)
    {
        $supplierGame = $this->getSupplierGame();
        if (!$supplierGame) {
            return $this->addError($attribute, 'Game chưa được đăng ký bởi nhà cung cấp');
        }
        if (!$supplierGame->isEnabled()) {
            return $this->addError($attribute, 'Game chưa được kích hoạt bởi nhà cung cấp');
        }
    }

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->game_id);
        }
        return $this->_game;
    }

    public function getSupplierGame()
    {
        if (!$this->_supplierGame) {
            $this->_supplierGame = SupplierGame::findOne(['supplier_id' => $this->supplier_id, 'game_id' => $this->game_id]);
        }
        return $this->_supplierGame;
    }

    public function change()
    {
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
        $game = $this->getSupplierGame();
        $game->auto_dispatcher = SupplierGame::AUTO_DISPATCHER_ON;
        return $game->save();
    }

    public function switchOff()
    {
        $game = $this->getSupplierGame();
        $game->auto_dispatcher = SupplierGame::AUTO_DISPATCHER_OFF;
        return $game->save();
    }
}
