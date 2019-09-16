<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Game;
use backend\models\GamePriceLog;

class FetchPriceLogForm extends Model
{
	public $game_id;

	protected $_command;

	public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = GamePriceLog::find();
        if ($this->game_id) {
            $command->andWhere(["game_id" => $this->game_id]);
        }
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function getGame()
    {
        if ($this->game_id) {
            return Game::findOne($this->game_id);
        }
        return null;
    }

}