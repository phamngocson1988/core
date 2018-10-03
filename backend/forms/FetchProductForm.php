<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Product;
use common\models\Game;
use yii\helpers\ArrayHelper;

class FetchProductForm extends Model
{
    public $q;
    public $game_id;
    public $status;
    protected $_command;
    protected $_game;

    public function rules()
    {
        return [
            [['game_id', 'q', 'status'], 'trim'],
            ['game_id', 'required'],
            ['game_id', 'validateGame'],
        ];
    }

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Product::find();
        $command->with('image');

        if ($this->game_id) {
            $command->andWhere(['game_id' => $this->game_id]);
        }

        if ($this->status) {
            $command->andWhere(['status' => $this->status]);
        }

        if ($this->q) {
            $command->andWhere(['like', 'title', $this->q]);
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

    public function validateGame($attribute, $params)
    {
        $game = $this->getGame();
        if (!$game) {
            $this->addError($attribute, Yii::t('app', 'game_is_required'));
        }
    }

    public function getGame()
    {
        if (!$this->_game) {
            $this->_game = Game::findOne($this->game_id);
        }
        return $this->_game;
    }
}
