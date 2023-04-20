<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\ResellerPrice;
use backend\models\User;
use backend\models\Game;
use yii\helpers\ArrayHelper;

class FetchResellerPriceForm extends Model
{
    public $reseller_id;
    public $game_id;
    public $invalid_at;

    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = ResellerPrice::find();

        $condition = ['reseller_id' => $this->reseller_id, 'game_id' => $this->game_id];
        $condition = array_filter($condition);
        if (count($condition)) {
            $command->where($condition);
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

    public function fetchResellers()
    {
        return ArrayHelper::map(User::find()->select(['id', 'name'])->where(['is_reseller' => User::IS_RESELLER])->all(), 'id', 'name');
    }

    public function fetchGames()
    {
        $games = Game::find()->where(['<>', 'status', Game::STATUS_DELETE])->select(['id', 'title', 'method_title', 'version_title', 'package_title'])->all();
        return ArrayHelper::map($games, 'id', function($model) {
            return sprintf("%s (%s-%s-%s)", $model->title, $model->method_title, $model->version_title, $model->package_title);
        });
    }
}
